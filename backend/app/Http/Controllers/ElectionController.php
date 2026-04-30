<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCandidatureRequest;
use App\Http\Resources\CandidatureResource;
use App\Http\Resources\ElectionResource;
use App\Models\AuditLog;
use App\Models\Candidature;
use App\Models\Election;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ElectionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Election::with('commission');

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        if ($request->has('statut')) {
            $query->where('statut', $request->statut);
        }

        $elections = $query->orderBy('date_vote', 'desc')->paginate(20);

        return response()->json([
            'success' => true,
            'data' => ElectionResource::collection($elections),
            'meta' => ['pagination' => [
                'current_page' => $elections->currentPage(),
                'last_page' => $elections->lastPage(),
                'per_page' => $elections->perPage(),
                'total' => $elections->total(),
            ]],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('election.gerer');

        $validated = $request->validate([
            'type' => 'required|in:bla,bca,be',
            'niveau' => 'required|string',
            'date_vote' => 'required|date',
            'heure_ouverture_vote' => 'required|date_format:H:i',
            'heure_cloture_vote' => 'required|date_format:H:i|after:heure_ouverture_vote',
            'mode_scrutin' => 'required|in:uninominal,plurinominal,majoritaire_simple,majoritaire_absolu',
            'postes' => 'required|array',
            'ouverture_candidatures' => 'required|date',
            'cloture_candidatures' => 'required|date|after:ouverture_candidatures',
        ]);

        $validated['statut'] = 'brouillon';
        $election = Election::create($validated);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'election.creer',
            'entite' => 'Election',
            'entite_id' => $election->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Élection créée.',
            'data' => new ElectionResource($election),
        ], 201);
    }

    public function show(Election $election): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new ElectionResource($election->load('commission', 'candidatures.alumni.user')),
        ]);
    }

    public function changerStatut(Request $request, Election $election): JsonResponse
    {
        $this->authorize('election.gerer');

        $request->validate([
            'statut' => 'required|in:brouillon,preparation,candidatures,campagne,vote,depouillement,proclame,archive',
        ]);

        $ancien = $election->statut;
        $election->update(['statut' => $request->statut]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'election.statut_change',
            'entite' => 'Election',
            'entite_id' => $election->id,
            'data' => ['ancien' => $ancien, 'nouveau' => $request->statut],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Statut mis à jour.',
            'data' => new ElectionResource($election),
        ]);
    }

    public function candidatures(Election $election): JsonResponse
    {
        $candidatures = $election->candidatures()
            ->with('alumni.user', 'alumni.cdej.cluster')
            ->orderBy('poste')
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => CandidatureResource::collection($candidatures),
        ]);
    }

    public function deposerCandidature(StoreCandidatureRequest $request, Election $election): JsonResponse
    {
        $this->authorize('candidature.soumettre');

        $data = $request->validated();
        $data['election_id'] = $election->id;
        $data['alumni_id'] = auth()->user()->alumniProfil?->id;

        if (!$data['alumni_id']) {
            return response()->json([
                'success' => false,
                'message' => 'Profil alumni requis.',
            ], 403);
        }

        $candidature = Candidature::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Candidature déposée.',
            'data' => new CandidatureResource($candidature),
        ], 201);
    }

    public function electeurs(Election $election): JsonResponse
    {
        $this->authorize('liste.electorale.voir');

        $service = app(\App\Services\VoteService::class);
        $inscrits = $service->nbElecteursInscrits($election);

        return response()->json([
            'success' => true,
            'data' => [
                'inscrits' => $inscrits,
                'niveau' => $election->niveau,
                'type' => $election->type,
            ],
        ]);
    }

    public function participation(Election $election): JsonResponse
    {
        $this->authorize('liste.electorale.voir');

        $inscrits = (new \App\Services\VoteService())->nbElecteursInscrits($election);
        $votants = $election->emargements()->count();
        $taux = $inscrits > 0 ? round(($votants / $inscrits) * 100, 2) : 0;

        return response()->json([
            'success' => true,
            'data' => [
                'inscrits' => $inscrits,
                'votants' => $votants,
                'taux' => $taux,
                'temps_restant' => $election->estOuverte() ? now()->diffInSeconds($election->heure_cloture_vote) : 0,
            ],
        ]);
    }

    public function proclamer(Election $election): JsonResponse
    {
        $this->authorize('election.proclamer');

        $election->update([
            'statut' => 'proclame',
            'proclame_at' => now(),
        ]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'election.proclamer',
            'entite' => 'Election',
            'entite_id' => $election->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Résultats proclamés.',
            'data' => new ElectionResource($election->load('resultats.candidature.alumni')),
        ]);
    }

    public function resultats(Election $election): JsonResponse
    {
        if ($election->statut !== 'proclame') {
            return response()->json([
                'success' => false,
                'message' => 'Les résultats ne sont pas encore proclamés.',
            ], 403);
        }

        $resultats = $election->resultats()
            ->with('candidature.alumni.user')
            ->orderBy('nb_voix', 'desc')
            ->get()
            ->groupBy('candidature.poste');

        $inscrits = (new \App\Services\VoteService())->nbElecteursInscrits($election);
        $votants = $election->emargements()->count();

        return response()->json([
            'success' => true,
            'data' => [
                'election' => new ElectionResource($election),
                'resultats' => $resultats,
                'participation' => [
                    'inscrits' => $inscrits,
                    'votants' => $votants,
                    'taux' => $inscrits > 0 ? round(($votants / $inscrits) * 100, 2) : 0,
                ],
            ],
        ]);
    }

    public function pv(Election $election): JsonResponse
    {
        $this->authorize('election.proclamer');

        $pdfService = app(\App\Services\PdfService::class);
        $url = $pdfService->genererPV($election);

        return response()->json([
            'success' => true,
            'data' => ['url' => $url],
        ]);
    }
}
