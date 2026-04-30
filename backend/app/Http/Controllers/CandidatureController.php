<?php

namespace App\Http\Controllers;

use App\Models\Candidature;
use App\Models\AuditLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CandidatureController extends Controller
{
    public function show(Candidature $candidature): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new \App\Http\Resources\CandidatureResource($candidature->load('alumni.user', 'election', 'validateur')),
        ]);
    }

    public function valider(Request $request, Candidature $candidature): JsonResponse
    {
        $this->authorize('candidature.valider');

        $candidature->update([
            'statut' => 'valide',
            'valide_par' => auth()->id(),
            'valide_at' => now(),
        ]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'candidature.valider',
            'entite' => 'Candidature',
            'entite_id' => $candidature->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Candidature validée.',
            'data' => new \App\Http\Resources\CandidatureResource($candidature),
        ]);
    }

    public function rejeter(Request $request, Candidature $candidature): JsonResponse
    {
        $this->authorize('candidature.rejeter');

        $request->validate([
            'motif' => 'required|string|min:10',
        ]);

        $candidature->update([
            'statut' => 'rejete',
            'motif_rejet' => $request->motif,
            'valide_par' => auth()->id(),
            'valide_at' => now(),
        ]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'candidature.rejeter',
            'entite' => 'Candidature',
            'entite_id' => $candidature->id,
            'data' => ['motif' => $request->motif],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Candidature rejetée.',
            'data' => new \App\Http\Resources\CandidatureResource($candidature),
        ]);
    }

    public function recours(Request $request, Candidature $candidature): JsonResponse
    {
        $request->validate([
            'arguments' => 'required|string|min:50',
        ]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'candidature.recours',
            'entite' => 'Candidature',
            'entite_id' => $candidature->id,
            'data' => ['arguments' => $request->arguments],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Recours déposé.',
        ]);
    }
}
