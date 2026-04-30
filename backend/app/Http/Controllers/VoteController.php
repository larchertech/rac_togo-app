<?php

namespace App\Http\Controllers;

use App\Http\Requests\CastVoteRequest;
use App\Http\Resources\ElecteurResource;
use App\Models\Alumni;
use App\Models\AuditLog;
use App\Models\Candidature;
use App\Models\Election;
use App\Models\Emargement;
use App\Models\Resultat;
use App\Services\VoteService;
use Illuminate\Http\JsonResponse;

class VoteController extends Controller
{
    public function __construct(private VoteService $voteService) {}

    public function listeElectorale(Election $election): JsonResponse
    {
        $this->authorize('liste.electorale.voir');

        $electeurs = match ($election->type) {
            'bla' => $this->getElecteursBLA($election),
            'bca' => $this->getElecteursBCA($election),
            'be' => $this->getElecteursBE($election),
        };

        return response()->json([
            'success' => true,
            'data' => ElecteurResource::collection($electeurs),
        ]);
    }

    public function voter(CastVoteRequest $request, Election $election): JsonResponse
    {
        $this->authorize('vote.cast');
        $electeur = auth()->user();

        if (!$election->estOuverte()) {
            return response()->json([
                'success' => false,
                'message' => 'Le bureau de vote est fermé.'
            ], 403);
        }

        if (!$this->voteService->estSurListeElectorale($election, $electeur)) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas sur la liste électorale.'
            ], 403);
        }

        $alumni = $electeur->alumniProfil;
        if (!$alumni) {
            return response()->json([
                'success' => false,
                'message' => 'Profil alumni non trouvé.'
            ], 403);
        }

        if (!$alumni->estEligibleVote() && !($election->config['exemption_cotisation'] ?? false)) {
            return response()->json([
                'success' => false,
                'message' => 'Votre cotisation n\'est pas à jour.'
            ], 403);
        }

        if (Emargement::where('election_id', $election->id)
            ->where('electeur_id', $electeur->id)
            ->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous avez déjà voté pour cette élection.'
            ], 403);
        }

        $this->voteService->casterVote($election, $electeur, $request->votes);

        return response()->json([
            'success' => true,
            'message' => 'Votre vote a été enregistré. Merci pour votre participation.'
        ]);
    }

    public function proclamer(Election $election): JsonResponse
    {
        $this->authorize('election.proclamer');

        $election->update([
            'statut' => 'proclame',
            'proclame_at' => now()
        ]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'election.proclamer',
            'entite' => 'Election',
            'entite_id' => $election->id,
        ]);

        return response()->json([
            'success' => true,
            'data' => new \App\Http\Resources\ElectionResource($election->load('resultats.candidature.alumni'))
        ]);
    }

    public function resultats(Election $election): JsonResponse
    {
        if ($election->statut !== 'proclame') {
            return response()->json([
                'success' => false,
                'message' => 'Les résultats ne sont pas encore proclamés.'
            ], 403);
        }

        $resultats = $election->resultats()
            ->with('candidature.alumni.user')
            ->orderBy('nb_voix', 'desc')
            ->get()
            ->groupBy('candidature.poste');

        $inscrits = $this->voteService->nbElecteursInscrits($election);
        $votants = Emargement::where('election_id', $election->id)->count();

        return response()->json([
            'success' => true,
            'data' => [
                'election' => new \App\Http\Resources\ElectionResource($election),
                'resultats' => $resultats,
                'participation' => [
                    'inscrits' => $inscrits,
                    'votants' => $votants,
                    'taux' => $inscrits > 0 ? round(($votants / $inscrits) * 100, 2) : 0,
                ]
            ]
        ]);
    }

    private function getElecteursBLA(Election $election)
    {
        $cdejId = $election->niveau;
        return Alumni::where('cdej_id', $cdejId)
            ->where('statut_compte', 'valide')
            ->with('user', 'cdej')
            ->get();
    }

    private function getElecteursBCA(Election $election)
    {
        $clusterId = $election->niveau;
        return Alumni::whereHas('cdej', fn($q) => $q->where('cluster_id', $clusterId))
            ->where('statut_compte', 'valide')
            ->with('user', 'cdej')
            ->get();
    }

    private function getElecteursBE(Election $election)
    {
        return Alumni::where('statut_compte', 'valide')
            ->with('user', 'cdej')
            ->get();
    }
}
