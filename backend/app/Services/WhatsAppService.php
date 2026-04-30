<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public function send(string $phone, string $message): bool
    {
        try {
            $token = config('services.whatsapp.token');
            $phoneId = config('services.whatsapp.phone_id');

            if (!$token || !$phoneId) {
                Log::warning('Configuration WhatsApp manquante.');
                return false;
            }

            // Normaliser le numéro
            $phone = $this->normaliserNumero($phone);

            $url = sprintf('https://graph.facebook.com/v18.0/%s/messages', $phoneId);

            $response = Http::withToken($token)
                ->retry(1, 1000)
                ->post($url, [
                    'messaging_product' => 'whatsapp',
                    'to' => $phone,
                    'type' => 'text',
                    'text' => [
                        'body' => $message,
                    ],
                ]);

            if ($response->successful()) {
                Log::info('Message WhatsApp envoyé avec succès.', ['phone' => $phone]);
                return true;
            }

            Log::error('Échec envoi WhatsApp.', [
                'phone' => $phone,
                'response' => $response->json(),
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('Exception WhatsApp : ' . $e->getMessage());
            return false;
        }
    }

    private function normaliserNumero(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (strpos($phone, '228') !== 0) {
            $phone = '228' . ltrim($phone, '0');
        }
        return $phone;
    }
}
