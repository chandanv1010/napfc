<?php  
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessTransactionHook;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;


class ThueApiController extends Controller {

    public function hook(Request $request){

        try {
            $mockData = $request->merge([
                "number" => "336883868386",
                "phone" => "0912345678",
                // "money" => 10000,
                "money" => 100000,
                "type" => "in",
                "gateway" => "acb",
                "txn_id" => "TXN987654",
                // "content" => "SHOPFC7356423347",
                "content" => "SHOPFC4094865651",
                "datetime" => "2025-10-19 14:10:00",
                "balance" => 123456789,
            ]);


            // $token = 'iNLBO81toIOWm5iUuAgghqVnxHGWP5blPMvMh3oL4JuPKrcEKA';
            // $thueapiToken = $request->header('X-Thueapi');
            // if ($token !== $thueapiToken) {

            //     return response([
            //         'success' => false,
            //         'message' => 'Token missmatch !'
            //     ], 401);
            // }

            $payload = $request->all();
            $content = strtoupper(trim($payload['content'] ?? ''));
            if (!$content) {
                Log::warning('⚠️ Webhook không có content, bỏ qua.');
                return response()->json([
                    'success' => false,
                    'message' => 'Thiếu nội dung content trong webhook.'
                ]);
            }

            if (str_starts_with($content, 'SHOPFC')) {
                Log::info("📩 Webhook [SHOPFC] nhận được giao dịch nạp thẻ", ['payload' => $payload]);
                ProcessTransactionHook::dispatch($payload);
                Log::info("🚀 Đã dispatch job ProcessTransactionHook (nạp thẻ) thành công.", [
                    'transaction_code' => $content,
                ]);
            } elseif (str_starts_with($content, 'SHOPACC')) {
                Log::info("📩 Webhook [SHOPACC] nhận được giao dịch mua account", ['payload' => $payload]);

                $this->processAccountTransaction($payload);

                Log::info("🎉 Đã xử lý xong giao dịch mua account", [
                    'transaction_code' => $content,
                ]);
            } else {
                Log::warning('⚠️ Nội dung chuyển khoản không hợp lệ hoặc không khớp định dạng:', ['content' => $content]);
            }


           
        

            return response()->json([
                'success' => true,
                'message' => 'Webhook đã được nhận, Đã thêm vào hàng đợi.',
                'data' => $payload
            ]);
        } catch (\Throwable $th) {
            Log::error('❌ Lỗi khi nhận webhook: '.$th->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xử lý webhook.'
            ], 500);
        }
    }

    private function processAccountTransaction(array $payload = []){
        $contentRaw = strtoupper(trim($payload['content'] ?? ''));
        preg_match('/SHOP(ACC|FC)[0-9]+/', $contentRaw, $matches);
        $content = $matches[0] ?? null;
        $amount  = (int)($payload['money'] ?? 0);
        if (!$content) {
            Log::warning('⚠️ Webhook account thiếu nội dung chuyển khoản.', $payload);
            return;
        }
        try {
            DB::beginTransaction();

            // 🔒 Tìm giao dịch tương ứng
            $transaction = Transaction::where('transaction_code', $content)
                ->where('type', 'account')
                ->lockForUpdate()
                ->first();
            

            if (!$transaction) {
                Log::warning("❌ Không tìm thấy transaction cho mã: {$content}");
                DB::rollBack();
                return;
            }

            // 🟡 Nếu giao dịch đã xử lý rồi thì bỏ qua
            if ($transaction->status === 'paid') {
                Log::info("⚠️ Transaction {$content} đã được thanh toán trước đó.");
                DB::rollBack();
                return;
            }

            // ✅ Kiểm tra số tiền khớp
            if ((int)$transaction->amount !== $amount) {
                Log::warning("⚠️ Sai số tiền khi mua account", [
                    'expected' => $transaction->amount,
                    'received' => $amount
                ]);
                $transaction->update([
                    'status' => 'failed',
                    'description' => 'Sai số tiền khi mua account.',
                ]);
                DB::commit();
                return;
            }

            // ✅ Cập nhật trạng thái giao dịch
            $transaction->update([
                'status' => 'paid',
                'paid_at' => now(),
                'description' => 'Webhook xác nhận mua account thành công.',
            ]);

            // ✅ Tạo Order mới
            $order = \App\Models\Order::firstOrCreate(
                ['transaction_id' => $transaction->id],
                [
                    'amount' => $transaction->amount,
                    'confirm' => 'processing',
                    'type' => $transaction->type,
                    'customer_id' => $transaction->customer_id,
                ]
            );

            // ✅ Gắn product vào order
            $order->products()->syncWithoutDetaching([
                $transaction->product_id => [
                    'uuid' => (string) \Illuminate\Support\Str::uuid(),
                    'name' => $transaction->type ?? 'Mua account',
                    'qty' => 1,
                    'price' => $transaction->amount,
                    'priceOriginal' => $transaction->amount,
                    'option' => json_encode([]),
                ],
            ]);

            Log::info("🧾 Đã tạo order #{$order->id} cho giao dịch {$transaction->transaction_code}");

        

            DB::commit();
            Log::info("🎉 Giao dịch mua account hoàn tất #{$transaction->transaction_code}");

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("❌ Lỗi xử lý processAccountTransaction: " . $th->getMessage(), [
                'trace' => $th->getTraceAsString(),
                'payload' => $payload
            ]);
        }

    }

    


}