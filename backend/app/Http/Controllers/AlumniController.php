<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAlumniRequest;
use App\Http\Resources\AlumniResource;
use App\Models\Alumni;
use App\Models\AuditLog;
use App\Services\PdfService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Alumni::with(['user', 'cdej.cluster']);

        if ($request->has('cdej_id')) {
            $query->where('cdej_id', $request->cdej_id);
        }
        if ($request->has('cluster_id')) {
            $query->whereHas('cdej', fn($q) => $q->where('cluster_id', $request->cluster_id));
        }
        if ($request->has('statut')) {
            $query->where('statut_compte', $request->statut);
        }
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(fn($q) => $q->where('nom', 'ilike', "%{$search}%")
                ->orWhere('prenom', 'ilike', "%{$search}%")
                ->orWhere('numero_membre', 'ilike', "%{$search}%"));
        }

        $alumni = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data' => AlumniResource::collection($alumni),
            'meta' => ['pagination' => [
                'current_page' => $alumni->currentPage(),
                'last_page' => $alumni->lastPage(),
                'per_page' => $alumni->perPage(),
                'total' => $alumni->total(),
            ]],
        ]);
    }

    public function store(StoreAlumniRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['statut_compte'] = 'en_attente';

        $alumni = Alumni::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Demande d\'inscription soumise avec succès.',
            'data' => new AlumniResource($alumni),
        ], 201);
    }

    public function show(Alumni $alumni): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new AlumniResource($alumni->load('user', 'cdej.cluster', 'cotisations')),
        ]);
    }

    public function update(Request $request, Alumni $alumni): JsonResponse
    {
        $this->authorize('profil.modifier');

        if (auth()->id() !== $alumni->user_id && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $alumni->update($request->only(['nom', 'prenom', 'date_naissance', 'niveau_diplome']));

        return response()->json([
            'success' => true,
            'message' => 'Profil mis à jour.',
            'data' => new AlumniResource($alumni),
        ]);
    }

    public function uploadDocument(Request $request, Alumni $alumni): JsonResponse
    {
        $request->validate([
            'document' => 'required|file|max:5120',
            'type' => 'required|string',
        ]);

        $path = $request->file('document')->store('documents/' . $alumni->id, 'cloudinary');

        $documents = $alumni->documents ?? [];
        $documents[] = [
            'type' => $request->type,
            'path' => $path,
            'uploaded_at' => now()->toDateTimeString(),
        ];
        $alumni->update(['documents' => $documents]);

        return response()->json([
            'success' => true,
            'message' => 'Document uploadé.',
            'data' => ['path' => $path],
        ]);
    }

    public function changerStatut(Request $request, Alumni $alumni): JsonResponse
    {
        $this->authorize('candidature.valider');

        $request->validate(['statut' => 'required|in:valide,rejete']);

        $ancienStatut = $alumni->statut_compte;
        $alumni->update(['statut_compte' => $request->statut]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'alumni.statut_change',
            'entite' => 'Alumni',
            'entite_id' => $alumni->id,
            'data' => ['ancien' => $ancienStatut, 'nouveau' => $request->statut],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Statut mis à jour.',
            'data' => new AlumniResource($alumni),
        ]);
    }

    public function genererCarte(Alumni $alumni): JsonResponse
    {
        $pdfService = app(PdfService::class);
        $url = $pdfService->genererCarteMembre($alumni);

        return response()->json([
            'success' => true,
            'data' => ['url' => $url],
        ]);
    }

    public function profilConnecte(Request $request): JsonResponse
    {
        $alumni = Alumni::with('user', 'cdej.cluster', 'cotisations')
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$alumni) {
            return response()->json([
                'success' => false,
                'message' => 'Profil alumni non trouvé.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new AlumniResource($alumni),
        ]);
    }
}
