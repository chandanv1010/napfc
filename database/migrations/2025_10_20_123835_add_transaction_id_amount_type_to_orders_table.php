<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('transaction_id')->nullable()->constrained()->onDelete('set null')->after('id');
            $table->integer('amount')->after('transaction_id')->default(0);
            $table->string('type')->nullable()->after('amount');
            $table->string('account')->nullable()->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Bắt lỗi mềm khi drop foreign key
            try {
                $table->dropForeign(['transaction_id']);
            } catch (\Throwable $e) {
                Log::warning('⚠️ Không tìm thấy foreign key orders_transaction_id_foreign để drop.');
            }

            // Drop từng cột nếu có
            foreach (['transaction_id', 'amount', 'type', 'account'] as $col) {
                if (Schema::hasColumn('orders', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
