<?php

namespace App\Services;

use App\Models\Election;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PdfService
{
    public function genererCarteMembre(\App\Models\Alumni $alumni): string
    {
        $pdf = Pdf::loadView('pdfs.carte-membre', [
            'alumni' => $alumni,
            'cdej' => $alumni->cdej,
            'cluster' => $alumni->cdej?->cluster,
            'date_emission' => now()->format('d/m/Y'),
        ]);

        $filename = sprintf('carte-membre-%s.pdf', $alumni->numero_membre);
        $path = 'cartes/' . $filename;
        Storage::disk('public')->put($path, $pdf->output());

        return Storage::disk('public')->url($path);
    }

    public function genererPV(Election $election): string
    {
        $resultats = $election->resultats()
            ->with('candidature.alumni')
            ->orderBy('nb_voix', 'desc')
            ->get()
            ->groupBy('candidature.poste');

        $inscrits = (new VoteService())->nbElecteursInscrits($election);
        $votants = $election->emargements()->count();
        $taux = $inscrits > 0 ? round(($votants / $inscrits) * 100, 2) : 0;

        $pdf = Pdf::loadView('pdfs.pv-election', [
            'election' => $election,
            'resultats' => $resultats,
            'inscrits' => $inscrits,
            'votants' => $votants,
            'taux_participation' => $taux,
            'commission' => $election->commission,
            'date_proclamation' => $election->proclame_at?->format('d/m/Y H:i'),
            'pv_numero' => sprintf('PV-%s-%s-%05d', strtoupper($election->type), date('Y'), $election->id),
        ]);

        $filename = sprintf('pv-election-%s-%s.pdf', $election->type, $election->id);
        $path = 'pv/' . $filename;
        Storage::disk('public')->put($path, $pdf->output());

        return Storage::disk('public')->url($path);
    }

    public function genererListeElectorale(Election $election): string
    {
        $electeurs = match ($election->type) {
            'bla' => \App\Models\Alumni::where('cdej_id', $election->niveau)
                ->where('statut_compte', 'valide')
                ->with('user', 'cdej')
                ->get(),
            'bca' => \App\Models\Alumni::whereHas('cdej', fn($q) => $q->where('cluster_id', $election->niveau))
                ->where('statut_compte', 'valide')
                ->with('user', 'cdej')
                ->get(),
            'be' => \App\Models\Alumni::where('statut_compte', 'valide')
                ->with('user', 'cdej')
                ->get(),
            default => collect(),
        };

        $pdf = Pdf::loadView('pdfs.liste-electorale', [
            'election' => $election,
            'electeurs' => $electeurs,
            'date_generation' => now()->format('d/m/Y H:i'),
        ]);

        $filename = sprintf('liste-electorale-%s-%s.pdf', $election->type, $election->id);
        $path = 'listes/' . $filename;
        Storage::disk('public')->put($path, $pdf->output());

        return Storage::disk('public')->url($path);
    }
}
