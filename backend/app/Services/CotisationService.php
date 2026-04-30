<?php

namespace App\Services;

use App\Models\Cotisation;
use Illuminate\Support\Facades\Log;

class CotisationService
{
    public function initierPaiement(int $alumniId, string $operateur, float $montant, int $annee): array
    {
        $reference = $this->genererReference($operateur);

        $cotisation = Cotisation::updateOrCreate(
            ['alumni_id' => $alumniId, 'annee' => $annee],
            [
                'montant' => $montant,
                'statut' => 'en_attente',
                'canal_paiement' => $operateur,
                'reference_externe' => $reference,
            ]
        );

        Log::info('Paiement initié.', [
            'cotisation_id' => $cotisation->id,
            'reference' => $reference,
            'operateur' => $operateur,
        ]);

        return [
            'cotisation_id' => $cotisation->id,
            'reference' => $reference,
            'montant' => $montant,
            'instructions' => $this->getInstructions($operateur, $reference, $montant),
        ];
    }

    public function verifierPaiement(string $reference): ?Cotisation
    {
        return Cotisation::where('reference_externe', $reference)->first();
    }

    public function confirmerPaiement(string $reference, array $data): bool
    {
        $cotisation = $this->verifierPaiement($reference);
        if (!$cotisation) {
            return false;
        }

        $cotisation->update([
            'statut' => 'paye',
            'paid_at' => now(),
            'recu_numero' => $data['recu_numero'] ?? $this->genererRecu(),
        ]);

        Log::info('Paiement confirmé.', ['cotisation_id' => $cotisation->id]);
        return true;
    }

    private function genererReference(string $operateur): string
    {
        $prefix = strtoupper(substr($operateur, 0, 2));
        $random = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 8));
        return sprintf('RAC%s%s%s', $prefix, date('Ymd'), $random);
    }

    private function genererRecu(): string
    {
        return sprintf('REC-%s-%06d', date('Y'), random_int(1, 999999));
    }

    private function getInstructions(string $operateur, string $reference, float $montant): string
    {
        return match ($operateur) {
            'flooz' => "Vous allez recevoir une demande de confirmation Flooz sur votre téléphone. Validez le paiement de {$montant} FCFA. Réf: {$reference}",
            'tmoney' => "Vous allez recevoir une demande de confirmation T-Money sur votre téléphone. Validez le paiement de {$montant} FCFA. Réf: {$reference}",
            default => "Référence de paiement : {$reference}. Montant : {$montant} FCFA.",
        };
    }

    public function rapportGlobal(int $annee = null): array
    {
        $annee = $annee ?? (int) date('Y');

        $totalAlumni = \App\Models\Alumni::where('statut_compte', 'valide')->count();
        $cotisationsPayees = Cotisation::where('annee', $annee)->where('statut', 'paye')->count();
        $cotisationsRetard = Cotisation::where('annee', $annee)->where('statut', 'en_retard')->count();
        $montantTotal = Cotisation::where('annee', $annee)->where('statut', 'paye')->sum('montant');

        return [
            'annee' => $annee,
            'total_alumni' => $totalAlumni,
            'cotisations_payees' => $cotisationsPayees,
            'cotisations_retard' => $cotisationsRetard,
            'taux_cotisation' => $totalAlumni > 0 ? round(($cotisationsPayees / $totalAlumni) * 100, 2) : 0,
            'montant_total' => (float) $montantTotal,
            'par_cluster' => $this->rapportParCluster($annee),
        ];
    }

    private function rapportParCluster(int $annee): array
    {
        return \App\Models\Cluster::withCount([
            'alumni as total',
            'alumni as payes' => function ($q) use ($annee) {
                $q->whereHas('cotisations', function ($sq) use ($annee) {
                    $sq->where('annee', $annee)->where('statut', 'paye');
                });
            }
        ])->get()->map(fn($c) => [
            'cluster' => $c->nom,
            'total' => $c->total,
            'payes' => $c->payes,
            'taux' => $c->total > 0 ? round(($c->payes / $c->total) * 100, 2) : 0,
        ])->toArray();
    }
}
