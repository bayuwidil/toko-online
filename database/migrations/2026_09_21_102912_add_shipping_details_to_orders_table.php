<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'shipping_name')) {
                $table->string('shipping_name')->nullable()->after('user_id');
            }

            if (!Schema::hasColumn('orders', 'shipping_phone')) {
                $table->string('shipping_phone', 30)->nullable();
            }

            if (!Schema::hasColumn('orders', 'shipping_address')) {
                $table->text('shipping_address')->nullable();
            }

            if (!Schema::hasColumn('orders', 'shipping_subdistrict')) {
                $table->string('shipping_subdistrict')->nullable();
            }

            if (!Schema::hasColumn('orders', 'shipping_district')) {
                $table->string('shipping_district')->nullable();
            }

            if (!Schema::hasColumn('orders', 'shipping_city')) {
                $table->string('shipping_city')->nullable();
            }

            if (!Schema::hasColumn('orders', 'shipping_province')) {
                $table->string('shipping_province')->nullable();
            }

            if (!Schema::hasColumn('orders', 'shipping_postal_code')) {
                $table->string('shipping_postal_code', 10)->nullable();
            }

            if (!Schema::hasColumn('orders', 'shipping_destination_id')) {
                $table->string('shipping_destination_id')->nullable();
            }

            if (!Schema::hasColumn('orders', 'shipping_weight')) {
                $table->unsignedInteger('shipping_weight')->default(0);
            }

            if (!Schema::hasColumn('orders', 'shipping_courier')) {
                $table->string('shipping_courier')->nullable();
            }

            if (!Schema::hasColumn('orders', 'shipping_service')) {
                $table->string('shipping_service')->nullable();
            }

            if (!Schema::hasColumn('orders', 'snap_token')) {
                $table->string('snap_token')->nullable();
            }

            if (!Schema::hasColumn('orders', 'shipping_destination_id')) {
                // no-op; index creation is skipped because column was just created above
            }
        });

        if (!Schema::hasColumn('orders', 'shipping_destination_id')) {
            // Fallback for databases that do not expose the new column to the closure check.
            // This will not run once the column exists.
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $columns = [
                'shipping_name',
                'shipping_phone',
                'shipping_address',
                'shipping_subdistrict',
                'shipping_district',
                'shipping_city',
                'shipping_province',
                'shipping_postal_code',
                'shipping_destination_id',
                'shipping_weight',
                'shipping_courier',
                'shipping_service',
                'snap_token',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
