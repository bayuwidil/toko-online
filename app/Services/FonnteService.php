<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class FonnteService
{
    public function send(string $phone, string $message): array
    {
        $phone = $this->normalizePhone($phone);

        $response = Http::timeout(30)
            ->withHeaders([
                'Authorization' => config('services.fonnte.token'),
            ])
            ->asMultipart()
            ->post(config('services.fonnte.url'), [
                [
                    'name' => 'target',
                    'contents' => $phone,
                ],
                [
                    'name' => 'message',
                    'contents' => $message,
                ],
                [
                    'name' => 'countryCode',
                    'contents' => '62',
                ],
            ]);

        if (!$response->successful()) {
            throw new Exception(
                'Gagal terhubung ke Fonnte: ' . $response->body()
            );
        }

        $result = $response->json();

        if (isset($result['status']) && $result['status'] === false) {
            throw new Exception(
                $result['reason'] ?? 'Pesan WhatsApp gagal dikirim.'
            );
        }

        return $result ?? [];
    }

    public function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '0')) {
            return '62' . substr($phone, 1);
        }

        if (str_starts_with($phone, '8')) {
            return '62' . $phone;
        }

        if (str_starts_with($phone, '62')) {
            return $phone;
        }

        return $phone;
    }
}