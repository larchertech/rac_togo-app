<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendOtpRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(private OtpService $otpService) {}

    public function sendOtp(SendOtpRequest $request): JsonResponse
    {
        $phone = $request->validated('phone');
        $canal = $request->validated('canal', 'whatsapp');

        $user = User::firstOrCreate(
            ['phone_whatsapp' => $phone],
            [
                'role' => 'alumni',
                'canal_prefere' => $canal,
            ]
        );

        if ($user->statut === 'suspendu') {
            return response()->json([
                'success' => false,
                'message' => 'Votre compte est suspendu. Contactez l\'administration.',
            ], 403);
        }

        $otp = $this->otpService->generer($user);
        $this->otpService->envoyer($user, $otp);

        return response()->json([
            'success' => true,
            'message' => 'Code envoyé avec succès.',
            'data' => [
                'canal' => $canal,
                'phone' => substr($phone, 0, 7) . '****',
            ],
        ]);
    }

    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        $phone = $request->validated('phone');
        $code = $request->validated('code');

        $user = User::where('phone_whatsapp', $phone)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé.',
            ], 404);
        }

        if (!$this->otpService->verifier($user, $code)) {
            return response()->json([
                'success' => false,
                'message' => sprintf('Code incorrect (%d/3 tentatives)', $user->otp_tentatives),
            ], 422);
        }

        $token = $user->createToken('rac-togo-api')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Connexion réussie.',
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'phone' => $user->phone_whatsapp,
                    'role' => $user->role,
                    'statut' => $user->statut,
                    'canal_prefere' => $user->canal_prefere,
                ],
            ],
        ]);
    }

    public function refresh(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->tokens()->delete();
        $token = $user->createToken('rac-togo-api')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Token rafraîchi.',
            'data' => ['token' => $token],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Déconnexion réussie.',
        ]);
    }
}
