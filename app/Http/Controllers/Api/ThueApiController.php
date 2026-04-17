<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessTransactionHook;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\Product;


class ThueApiController extends Controller
{

    public function hook(Request $request)
    {
        try {

            $token = config('app.thue_api_token');
            $thueapiToken = $request->header('X-Thueapi');
            if ($token !== $thueapiToken) {
                return response([
                    'success' => false,
                    'message' => 'Token missmatch !'
                ], 401);
            }


            $content = strtoupper(trim($request->input('content', '')));
            $money = $request->has('money') ? (int)$request->input('money') : null;

            if (empty($content) || $money === null) {
                return response()->noContent();
            }

            $payload = [
                "number" => $request->input('number', "336883868386"),
                "phone" => $request->input('phone', "0912345678"),
                "money" => $money,
                "type" => $request->input('type', "in"),
                "gateway" => $request->input('gateway', "acb"),
                "txn_id" => $request->input('txn_id', "TXN987654"),
                "content" => $content,
                "datetime" => $request->input('datetime', "2025-10-19 14:10:00"),
                "balance" => $request->input('balance', 123456789),
            ];

            $content = strtoupper(trim($payload['content'] ?? ''));
            if (!$content) {
                Log::warning('⚠️ Webhook không có content, bỏ qua.', ['payload' => $payload]);
                return response()->noContent();
            }

            if (str_starts_with($content, 'SHOPFC')) {
                Log::info("📩 Webhook [SHOPFC] nhận được giao dịch nạp thẻ", ['payload' => $payload]);
                ProcessTransactionHook::dispatch($payload);
                Log::info("🚀 Đã dispatch job ProcessTransactionHook (nạp thẻ) thành công.", [
                    'transaction_code' => $content,
                ]);
            } elseif (str_starts_with($content, 'SHOPACC')) {
                Log::info("📩 Webhook [SHOPACC] nhận được giao dịch mua account", ['payload' => $payload]);

                $processed = $this->processAccountTransaction($payload);

                if ($processed) {
                    Log::info("🎉 Đã xử lý xong giao dịch mua account", [
                        'transaction_code' => $content,
                    ]);
                } else {
                    Log::warning('⚠️ Không tìm thấy giao dịch hoặc giao dịch đã được xử lý trước đó.', [
                        'transaction_code' => $content,
                        'payload' => $payload
                    ]);
                }
            } else {
                Log::warning('⚠️ Nội dung chuyển khoản không hợp lệ hoặc không khớp định dạng:', [
                    'content' => $content,
                    'payload' => $payload
                ]);
            }

            return response()->noContent();
        } catch (\Throwable $th) {
            Log::error('❌ Lỗi khi nhận webhook: ' . $th->getMessage(), [
                'trace' => $th->getTraceAsString(),
                'payload' => $request->all()
            ]);
            return response()->noContent();
        }
    }

    private function processAccountTransaction(array $payload = []): bool
    {
        $contentRaw = strtoupper(trim($payload['content'] ?? ''));
        preg_match('/SHOP(ACC|FC)[0-9]+/', $contentRaw, $matches);
        $content = $matches[0] ?? null;
        $amount  = (int)($payload['money'] ?? 0);
        if (!$content) {
            Log::warning('⚠️ Webhook account thiếu nội dung chuyển khoản.', $payload);
            return false;
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
                return false;
            }

            // 🟡 Nếu giao dịch đã xử lý rồi thì bỏ qua
            if ($transaction->status === 'paid') {
                Log::info("⚠️ Transaction {$content} đã được thanh toán trước đó.");
                DB::rollBack();
                return false;
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
                return false;
            }

            // ✅ Cập nhật trạng thái giao dịch
            $transaction->update([
                'status' => 'paid',
                'paid_at' => now(),
                'description' => 'Webhook xác nhận mua account thành công.',
            ]);

            // ✅ Cập nhật publish của sản phẩm từ 2 về 1 để không hiển thị nữa
            if ($transaction->product_id) {
                $product = Product::find($transaction->product_id);
                if ($product && $product->publish == 2) {
                    $product->update(['publish' => 1]);
                    Log::info("🔒 Đã cập nhật publish sản phẩm #{$product->id} từ 2 về 1");
                }
            }

            DB::commit();
            Log::info("🎉 Giao dịch mua account hoàn tất #{$transaction->transaction_code}");
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("❌ Lỗi xử lý processAccountTransaction: " . $th->getMessage(), [
                'trace' => $th->getTraceAsString(),
                'payload' => $payload
            ]);
            return false;
        }
    }
}
