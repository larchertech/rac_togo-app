<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\AuditLog;
use App\Models\Cluster;
use App\Models\Cotisation;
use App\Models\Election;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        $totalAlumni = Alumni::where('statut_compte', 'valide')->count();
        $totalCdej = \App\Models\Cdej::count();
        $totalClusters = Cluster::count();
        $candidaturesRecues = \App\Models\Candidature::count();

        return response()->json([
            'success' => true,
            'data' => [
                'alumni' => $totalAlumni,
                'cdej' => $totalCdej,
                'clusters' => $totalClusters,
                'candidatures_recues' => $candidaturesRecues,
            ],
        ]);
    }

    public function clusters(): JsonResponse
    {
        $clusters = Cluster::withCount([
            'alumni as total',
            'alumni as actifs' => function ($q) {
                $q->where('statut_compte', 'valide');
            }
        ])->get()->map(fn($c) => [
            'nom' => $c->nom,
            'region' => $c->region,
            'total' => $c->total,
            'actifs' => $c->actifs,
        ]);

        return response()->json([
            'success' => true,
            'data' => $clusters,
        ]);
    }

    public function activite(): JsonResponse
    {
        $logs = AuditLog::with('user')
            ->latest()
            ->limit(50)
            ->get()
            ->map(fn($log) => [
                'action' => $log->action,
                'user' => $log->user?->name ?? 'Système',
                'entite' => $log->entite,
                'date' => $log->created_at->format('d/m/Y H:i'),
            ]);

        return response()->json([
            'success' => true,
            'data' => $logs,
        ]);
    }

    public function electoral(): JsonResponse
    {
        $phases = [
            ['nom' => 'Inscriptions', 'debut' => '2026-01-01', 'fin' => '2026-03-31', 'statut' => 'termine'],
            ['nom' => 'BLA', 'debut' => '2026-04-01', 'fin' => '2026-05-09', 'statut' => 'en_cours'],
            ['nom' => 'BCA', 'debut' => '2026-05-10', 'fin' => '2026-05-30', 'statut' => 'a_venir'],
            ['nom' => 'BE', 'debut' => '2026-06-01', 'fin' => '2026-06-20', 'statut' => 'a_venir'],
            ['nom' => 'Installation', 'debut' => '2026-06-21', 'fin' => '2026-07-15', 'statut' => 'a_venir'],
        ];

        $elections = Election::actives()->get()->map(fn($e) => [
            'type' => $e->type,
            'niveau' => $e->niveau,
            'statut' => $e->statut,
            'date_vote' => $e->date_vote?->format('d/m/Y'),
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'phases' => $phases,
                'elections_actives' => $elections,
            ],
        ]);
    }

    public function alertes(): JsonResponse
    {
        $alertes = [];

        $retards = Cotisation::where('annee', (int) date('Y'))
            ->where('statut', 'en_retard')
            ->count();

        if ($retards > 0) {
            $alertes[] = [
                'type' => 'warning',
                'message' => "{$retards} cotisations en retard pour l'année en cours.",
            ];
        }

        $incidents = AuditLog::where('action', 'like', '%.erreur%')
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        if ($incidents > 0) {
            $alertes[] = [
                'type' => 'danger',
                'message' => "{$incidents} incident(s) signalé(s) cette semaine.",
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $alertes,
        ]);
    }
}
