<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    public function send(string $phone, string $message): bool
    {
        $token = env('WHATSAPP_API_TOKEN');

        $response = Http::timeout(15)
            ->withHeaders([
                'Authorization' => $token,
            ])
            ->post(
                env('WHATSAPP_API_URL'),
                [
                    'target' => $phone,
                    'message' => $message,
                ]
            );

        return $response->successful();
    }
}