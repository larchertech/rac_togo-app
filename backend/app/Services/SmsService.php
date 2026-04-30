<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public function send(string $phone, string $message): bool
    {
        try {
            $username = config('services.africastalking.username');
            $apiKey = config('services.africastalking.api_key');
            $sender = config('services.africastalking.sender', 'RAC-TOGO');

            if (!$username || !$apiKey) {
                Log::warning('Configuration Africa\'s Talking manquante.');
                return false;
            }

            $phone = $this->normaliserNumero($phone);

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'apiKey' => $apiKey,
            ])->post('https://api.africastalking.com/version1/messaging', [
                'username' => $username,
                'to' => $phone,
                'message' => $message,
                'from' => $sender,
            ]);

            if ($response->successful()) {
                Log::info('SMS envoyé avec succès.', ['phone' => $phone]);
                return true;
            }

            Log::error('Échec envoi SMS.', [
                'phone' => $phone,
                'response' => $response->body(),
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('Exception SMS : ' . $e->getMessage());
            return false;
        }
    }

    private function normaliserNumero(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (strpos($phone, '228') !== 0) {
            $phone = '228' . ltrim($phone, '0');
        }
        return '+' . $phone;
    }
}
