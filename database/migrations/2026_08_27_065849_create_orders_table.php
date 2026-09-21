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
        Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->string('order_number')->unique(); // Misal: INV-20231024-001
        
        // Total Harga
        $table->decimal('subtotal', 15, 2);
        $table->decimal('shipping_cost', 15, 2);
        $table->decimal('grand_total', 15, 2);
        
        // Status & Pengiriman
        $table->enum('status', ['pending', 'processing', 'shipped', 'completed', 'cancelled'])->default('pending');
        $table->enum('payment_status', ['unpaid', 'paid', 'failed'])->default('unpaid');
        $table->string('shipping_courier')->nullable(); // jne, tiki, pos
        $table->string('tracking_number')->nullable(); // Resi
        $table->text('shipping_address');
        
        // Payment Gateway token (untuk Midtrans dsb)
        $table->string('snap_token')->nullable();
        
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
