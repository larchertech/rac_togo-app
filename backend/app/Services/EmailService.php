<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    public function sendOtp(string $email, string $otp): bool
    {
        try {
            Mail::raw(
                "Votre code de vérification RAC-TOGO est : {$otp}\n\nCe code expire dans 10 minutes. Ne le partagez avec personne.",
                function ($message) use ($email) {
                    $message->to($email)
                        ->subject('Code de vérification RAC-TOGO');
                }
            );

            Log::info('Email OTP envoyé.', ['email' => $email]);
            return true;
        } catch (\Exception $e) {
            Log::error('Exception Email OTP : ' . $e->getMessage());
            return false;
        }
    }

    public function send(string $email, string $subject, string $body): bool
    {
        try {
            Mail::raw($body, function ($message) use ($email, $subject) {
                $message->to($email)->subject($subject);
            });

            Log::info('Email envoyé.', ['email' => $email]);
            return true;
        } catch (\Exception $e) {
            Log::error('Exception Email : ' . $e->getMessage());
            return false;
        }
    }
}
