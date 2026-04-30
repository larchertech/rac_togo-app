<?php

namespace App\Http\Controllers;

use App\Models\Cotisation;
use App\Services\CotisationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CotisationController extends Controller
{
    public function __construct(private CotisationService $cotisationService) {}

    public function statut(Request $request): JsonResponse
    {
        $alumni = $request->user()->alumniProfil;
        if (!$alumni) {
            return response()->json([
                'success' => false,
                'message' => 'Profil alumni non trouvé.',
            ], 404);
        }

        $annee = (int) date('Y');
        $cotisation = Cotisation::where('alumni_id', $alumni->id)
            ->where('annee', $annee)
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'annee' => $annee,
                'statut' => $cotisation?->statut ?? 'en_attente',
                'montant' => $cotisation?->montant ?? 5000,
                'a_jour' => $alumni->estEligibleVote(),
                'historique' => $alumni->cotisations()->orderBy('annee', 'desc')->get(),
            ],
        ]);
    }

    public function initier(Request $request): JsonResponse
    {
        $request->validate([
            'operateur' => 'required|in:flooz,tmoney',
            'annee' => 'required|integer',
        ]);

        $alumni = $request->user()->alumniProfil;
        if (!$alumni) {
            return response()->json([
                'success' => false,
                'message' => 'Profil alumni non trouvé.',
            ], 404);
        }

        $result = $this->cotisationService->initierPaiement(
            $alumni->id,
            $request->operateur,
            5000,
            $request->annee
        );

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }

    public function webhookFlooz(Request $request): JsonResponse
    {
        $request->validate([
            'reference' => 'required|string',
            'statut' => 'required|string',
            'recu' => 'nullable|string',
        ]);

        if ($request->statut === 'succes') {
            $this->cotisationService->confirmerPaiement($request->reference, [
                'recu_numero' => $request->recu,
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function webhookTmoney(Request $request): JsonResponse
    {
        $request->validate([
            'reference' => 'required|string',
            'statut' => 'required|string',
            'recu' => 'nullable|string',
        ]);

        if ($request->statut === 'succes') {
            $this->cotisationService->confirmerPaiement($request->reference, [
                'recu_numero' => $request->recu,
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function rapport(Request $request): JsonResponse
    {
        $this->authorize('rapport.financier');

        $annee = $request->get('annee', (int) date('Y'));
        $rapport = $this->cotisationService->rapportGlobal($annee);

        return response()->json([
            'success' => true,
            'data' => $rapport,
        ]);
    }

    public function relances(Request $request): JsonResponse
    {
        $this->authorize('rapport.financier');

        $annee = (int) date('Y');
        $alumniEnRetard = Cotisation::where('annee', $annee)
            ->where('statut', 'en_retard')
            ->with('alumni.user')
            ->get();

        $count = 0;
        foreach ($alumniEnRetard as $cotisation) {
            if ($cotisation->alumni?->user?->phone_whatsapp) {
                app(\App\Services\WhatsAppService::class)->send(
                    $cotisation->alumni->user->phone_whatsapp,
                    "Bonjour {$cotisation->alumni->prenom}, votre cotisation RAC-TOGO {$annee} est en retard. Montant : 5000 FCFA. Paiement via Flooz ou T-Money disponible sur l'application. Merci !"
                );
                $count++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$count} relances envoyées.",
        ]);
    }
}
