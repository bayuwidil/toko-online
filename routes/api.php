<?php
use App\Http\Controllers\Api\MidtransWebhookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MidtransNotificationController;

// Endpoint: https://domain-anda.com/api/midtrans/webhook
Route::post('/midtrans/webhook', [MidtransWebhookController::class, 'handle']);

Route::post(
    '/midtrans/notification',
    [MidtransNotificationController::class, 'handle']
);