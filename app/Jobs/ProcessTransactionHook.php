<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;
use App\Models\Order;
use Illuminate\Support\Facades\Http;

class ProcessTransactionHook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $payload;

    public $tries = 3;

    public $backoff = 300;

    /**
     * Create a new job instance.
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $contentRaw = strtoupper(trim($this->payload['content'] ?? ''));
        preg_match('/SHOP(ACC|FC)[0-9]+/', $contentRaw, $matches);
        $content = $matches[0] ?? null;
                

        $transactionCode = $content ?? null;
        // $amount          = $this->payload['money'] ?? 0;

        if (!$transactionCode) {
            Log::warning('⚠️ Thiếu transaction_code trong payload.', $this->payload);
            return;
        }

        try {
            DB::beginTransaction();

            // 🔒 Khóa hàng để tránh xử lý trùng
            $transaction = Transaction::where('transaction_code', $transactionCode)
                ->lockForUpdate()
                ->first();

            Log::info('Transaction: ', [$transaction]);

            // die();

            if (!$transaction) {
                Log::warning("❌ Không tìm thấy transaction: {$transactionCode}");
                DB::rollBack();
                return;
            }

            // 🟡 Nếu đã paid rồi => bỏ qua
            if ($transaction->status !== 'pending') {
                Log::info("⚠️ Transaction {$transactionCode} đã xử lý trước đó ({$transaction->status}).");
                DB::rollBack();
                return;
            }

            // ✅ 1. Đánh dấu transaction đã thanh toán
            $transaction->update([
                'status'  => 'paid',
                'paid_at' => now(),
                'description'    => 'Webhook xác nhận đã nhận tiền',
            ]);
            Log::info('Transaction: ', $transaction->toArray());
            DB::commit();
            // ✅ 3. Gọi Python để xử lý nạp tiền
            $this->callPythonRecharge($transaction);

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("❌ Lỗi trong ProcessTransactionHook: " . $th->getMessage(), [
                'trace' => $th->getTraceAsString(),
                'payload' => $this->payload
            ]);

            // Laravel Queue sẽ tự động retry nếu job thất bại
            throw $th;
        }
    }

    protected function callPythonRecharge($transaction)
    {
        try {
            // ✅ Đảm bảo dùng URL production, không phải localhost
            $url = env('PYTHON_RECHARGE_URL', 'https://api.ttcnfco.com/auto-tool');
            $apiKey = env('PYTHON_API_KEY', 'HTVIETNAM_CHANDANV1010@GMAIL.COM');

            $payload = [
                'amount' => (string)($transaction->amount/1000),
                'account' => $transaction->account,
                'transaction_code' => $transaction->transaction_code,
                'quantity' => $transaction->quantity
            ];

            // Log trước khi gọi để debug
            Log::info("📤 Đang gọi Python API:", [
                'url' => $url,
                'payload' => $payload,
            ]);

            // ✅ Thêm Content-Type và timeout
            $response = Http::timeout(30)
                ->withHeaders([
                    'X-API-Key' => $apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->asJson() // Đảm bảo gửi dạng JSON
                ->post($url, $payload);

            // Log response
            Log::info("📥 Response từ Python API:", [
                'status' => $response->status(),
                'successful' => $response->successful(),
            ]);

            if ($response->successful()) {
                Log::info("✅ Đã gửi yêu cầu nạp tiền sang Python thành công:", $response->json());
            } else {
                Log::error("❌ Gửi sang Python thất bại:", [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'headers' => $response->headers(),
                ]);
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // Lỗi kết nối riêng (timeout, DNS, connection refused)
            Log::error("🚨 Lỗi kết nối khi gọi FastAPI: " . $e->getMessage(), [
                'url' => $url ?? 'N/A',
                'payload' => $payload ?? null,
            ]);
        } catch (\Throwable $e) {
            Log::error("🚨 Lỗi khi gọi FastAPI: " . $e->getMessage(), [
                'url' => $url ?? 'N/A',
                'payload' => $payload ?? null,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
