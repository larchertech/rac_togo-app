<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class OtpService
{
    public function generer(User $user): string
    {
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->update([
            'otp_code' => Hash::make($code),
            'otp_expires_at' => now()->addMinutes(10),
            'otp_tentatives' => 0,
        ]);

        return $code;
    }

    public function envoyer(User $user, string $otp): void
    {
        $message = sprintf(
            "Votre code de vérification RAC-TOGO est : %s\n\nCe code expire dans 10 minutes. Ne le partagez avec personne.",
            $otp
        );

        if ($user->phone_whatsapp && $user->canal_prefere === 'whatsapp') {
            app(WhatsAppService::class)->send($user->phone_whatsapp, $message);
            $canal = 'whatsapp';
        } elseif ($user->email && $user->canal_prefere === 'email') {
            app(EmailService::class)->sendOtp($user->email, $otp);
            $canal = 'email';
        } elseif ($user->phone_whatsapp) {
            app(SmsService::class)->send($user->phone_whatsapp, $message);
            $canal = 'sms';
        } else {
            throw new \RuntimeException('Aucun canal de communication disponible pour cet utilisateur.');
        }

        Notification::create([
            'destinataire_id' => $user->id,
            'canal' => $canal,
            'type' => 'otp',
            'message' => $message,
            'statut' => 'envoye',
            'sent_at' => now(),
        ]);
    }

    public function verifier(User $user, string $code): bool
    {
        if ($user->otp_tentatives >= 3) {
            abort(429, 'Trop de tentatives. Veuillez demander un nouveau code.');
        }

        if (!$user->otp_expires_at || now()->gt($user->otp_expires_at)) {
            abort(422, 'Le code a expiré. Veuillez demander un nouveau code.');
        }

        if (Hash::check($code, $user->otp_code)) {
            $user->update([
                'otp_code' => null,
                'otp_expires_at' => null,
                'otp_tentatives' => 0,
                'derniere_connexion' => now(),
            ]);
            return true;
        }

        $user->increment('otp_tentatives');
        return false;
    }
}
