<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class PaymentStatusSyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_success_page_updates_unpaid_order_from_midtrans_status(): void
    {
        $mock = Mockery::mock('alias:Midtrans\Transaction');
        $mock->shouldReceive('status')
            ->with('INV-TEST-001')
            ->andReturn((object) [
                'transaction_status' => 'settlement',
                'fraud_status' => null,
            ]);

        $user = User::factory()->create();

        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => 'INV-TEST-001',
            'subtotal' => 200000,
            'shipping_cost' => 50000,
            'grand_total' => 250000,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'shipping_courier' => 'jne',
            'shipping_address' => 'Jl. Contoh No. 10',
            'snap_token' => 'token-test',
        ]);

        $response = $this->get('/payment/success?order_id=' . $order->order_number);

        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => 'paid',
            'status' => 'processing',
        ]);
    }
}
