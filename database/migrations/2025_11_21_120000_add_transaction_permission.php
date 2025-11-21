<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $permissionId = DB::table('permissions')
            ->where('canonical', 'transaction.index')
            ->value('id');

        if (!$permissionId) {
            $permissionId = DB::table('permissions')->insertGetId([
                'name' => 'Quản lý giao dịch',
                'canonical' => 'transaction.index',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        if ($permissionId) {
            $catalogueIds = DB::table('user_catalogues')->pluck('id');

            foreach ($catalogueIds as $catalogueId) {
                $exists = DB::table('user_catalogue_permission')
                    ->where('permission_id', $permissionId)
                    ->where('user_catalogue_id', $catalogueId)
                    ->exists();

                if (!$exists) {
                    DB::table('user_catalogue_permission')->insert([
                        'permission_id' => $permissionId,
                        'user_catalogue_id' => $catalogueId,
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $permissionId = DB::table('permissions')
            ->where('canonical', 'transaction.index')
            ->value('id');

        if ($permissionId) {
            DB::table('user_catalogue_permission')
                ->where('permission_id', $permissionId)
                ->delete();

            DB::table('permissions')
                ->where('id', $permissionId)
                ->delete();
        }
    }
};
