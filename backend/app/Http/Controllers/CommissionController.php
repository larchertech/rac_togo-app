<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use App\Models\Election;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Commission::query();

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        if ($request->has('niveau')) {
            $query->where('niveau', $request->niveau);
        }

        $commissions = $query->get();

        return response()->json([
            'success' => true,
            'data' => $commissions,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('election.gerer');

        $validated = $request->validate([
            'type' => 'required|in:cena,cec,cel',
            'niveau' => 'required|string',
            'membres' => 'required|array',
            'membres.*' => 'integer|exists:users,id',
        ]);

        $commission = Commission::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Commission créée.',
            'data' => $commission,
        ], 201);
    }

    public function show(Commission $commission): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $commission->load('membresDetails'),
        ]);
    }

    public function update(Request $request, Commission $commission): JsonResponse
    {
        $this->authorize('election.gerer');

        $commission->update($request->only(['membres', 'config']));

        return response()->json([
            'success' => true,
            'message' => 'Commission mise à jour.',
            'data' => $commission,
        ]);
    }

    public function dashboard(): JsonResponse
    {
        $this->authorize('liste.electorale.voir');

        $candidaturesStats = \App\Models\Candidature::selectRaw("
            count(*) as total,
            sum(case when statut = 'soumis' then 1 else 0 end) as soumis,
            sum(case when statut = 'en_examen' then 1 else 0 end) as en_examen,
            sum(case when statut = 'valide' then 1 else 0 end) as valide,
            sum(case when statut = 'rejete' then 1 else 0 end) as rejete
        ")->first();

        $electionsEnCours = Election::actives()->count();
        $alertes = [];

        if ($candidaturesStats->en_examen > 0) {
            $alertes[] = [
                'type' => 'warning',
                'message' => "{$candidaturesStats->en_examen} candidature(s) en attente d'examen.",
            ];
        }

        $electionsVote = Election::where('statut', 'vote')->get();
        foreach ($electionsVote as $election) {
            $taux = $election->tauxParticipation();
            if ($taux < 30) {
                $alertes[] = [
                    'type' => 'danger',
                    'message' => "Taux de participation faible ({$taux}%) pour l'élection {$election->type}.",
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'candidatures' => $candidaturesStats,
                'elections_en_cours' => $electionsEnCours,
                'alertes' => $alertes,
            ],
        ]);
    }
}
