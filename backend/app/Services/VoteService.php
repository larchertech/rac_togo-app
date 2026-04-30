<?php

namespace App\Services;

use App\Models\Alumni;
use App\Models\AuditLog;
use App\Models\Cdej;
use App\Models\Candidature;
use App\Models\Cluster;
use App\Models\Election;
use App\Models\Emargement;
use App\Models\Resultat;
use Illuminate\Support\Facades\DB;

class VoteService
{
    public function casterVote(Election $election, \App\Models\User $electeur, array $votes): void
    {
        if (!$election->estOuverte()) {
            abort(403, 'Le bureau de vote est fermé.');
        }

        if (!$this->estSurListeElectorale($election, $electeur)) {
            abort(403, 'Vous n\'êtes pas sur la liste électorale.');
        }

        $alumni = $electeur->alumniProfil;
        if (!$alumni) {
            abort(403, 'Profil alumni non trouvé.');
        }

        $exemption = $election->config['exemption_cotisation'] ?? false;
        if (!$alumni->estEligibleVote() && !$exemption) {
            abort(403, 'Votre cotisation n\'est pas à jour.');
        }

        if (Emargement::where('election_id', $election->id)
            ->where('electeur_id', $electeur->id)
            ->exists()) {
            abort(403, 'Vous avez déjà voté pour cette élection.');
        }

        DB::transaction(function () use ($election, $electeur, $votes) {
            foreach ($votes as $candidatureId) {
                $candidatureId = (int) $candidatureId;

                // Vérifier que la candidature appartient à cette élection
                $exists = Candidature::where('id', $candidatureId)
                    ->where('election_id', $election->id)
                    ->where('statut', 'valide')
                    ->exists();

                if (!$exists) {
                    abort(422, 'Candidature invalide.');
                }

                Resultat::where('election_id', $election->id)
                    ->where('candidature_id', $candidatureId)
                    ->lockForUpdate()
                    ->increment('nb_voix');
            }

            Emargement::create([
                'election_id' => $election->id,
                'electeur_id' => $electeur->id,
                'voted_at' => now(),
            ]);

            AuditLog::create([
                'user_id' => $electeur->id,
                'action' => 'vote.cast',
                'entite' => 'Election',
                'entite_id' => $election->id,
                'data' => [
                    'election_type' => $election->type,
                    'election_niveau' => $election->niveau,
                    'nb_postes' => count($votes),
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        });
    }

    public function estSurListeElectorale(Election $election, \App\Models\User $electeur): bool
    {
        $alumni = $electeur->alumniProfil;
        if (!$alumni || $alumni->statut_compte !== 'valide') {
            return false;
        }

        return match ($election->type) {
            'bla' => $alumni->cdej_id == $election->niveau,
            'bca' => $alumni->cdej && $alumni->cdej->cluster_id == $election->niveau,
            'be' => true,
            default => false,
        };
    }

    public function nbElecteursInscrits(Election $election): int
    {
        return match ($election->type) {
            'bla' => Alumni::where('cdej_id', $election->niveau)
                ->where('statut_compte', 'valide')
                ->count(),
            'bca' => Alumni::whereHas('cdej', fn($q) => $q->where('cluster_id', $election->niveau))
                ->where('statut_compte', 'valide')
                ->count(),
            'be' => Alumni::where('statut_compte', 'valide')->count(),
            default => 0,
        };
    }
}
