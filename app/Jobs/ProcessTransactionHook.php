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
        $transactionCode = $this->payload['content'] ?? null;
        $amount          = $this->payload['money'] ?? 0;

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

            // ✅ 2. Tạo Order (nếu chưa có)
            $order = Order::firstOrCreate(
                ['transaction_id' => $transaction->id],
                [
                    'account'     => $transaction->account,
                    'amount'      => $transaction->amount,
                    'confirm'      => 'processing',
                    'type'        => $transaction->type,
                    'customer_id' => $transaction->customer_id,
                ]
            );

            Log::info('Order: ', $order->toArray());
            Log::info('Transaction: ', $transaction->toArray());

            // die();

            if ($order->id) {
                $order->products()->attach($transaction->product_id, [
                    'uuid'          => (string) \Illuminate\Support\Str::uuid(),
                    'name'          => $transaction->type ?? 'Nạp tiền',
                    'qty'           => 1,
                    'price'         => $transaction->amount,
                    'priceOriginal' => $transaction->amount,
                    'option'        => json_encode([]),
                ]);

                Log::info("🧩 Đã thêm product #{$transaction->product_id} vào order #{$order->id}");
            }

            DB::commit();

            Log::info("🧾 Đã tạo Order #{$order->id} cho giao dịch {$transactionCode}.");

            // ✅ 3. Gọi Python để xử lý nạp tiền
            // $this->callPythonRecharge($order, $transaction);

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
}
