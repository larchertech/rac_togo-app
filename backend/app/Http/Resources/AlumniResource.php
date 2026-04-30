<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlumniResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'nom_complet' => $this->nom_complet,
            'date_naissance' => $this->date_naissance?->format('d/m/Y'),
            'niveau_diplome' => $this->niveau_diplome,
            'documents' => $this->documents,
            'statut_compte' => $this->statut_compte,
            'numero_membre' => $this->numero_membre,
            'est_eligible_vote' => $this->estEligibleVote(),
            'cotisation_a_jour' => $this->cotisationAJour(),
            'cdej' => $this->whenLoaded('cdej', fn() => [
                'id' => $this->cdej->id,
                'nom' => $this->cdej->nom,
                'ville' => $this->cdej->ville,
                'cluster' => $this->whenLoaded('cdej.cluster', fn() => [
                    'id' => $this->cdej->cluster->id,
                    'nom' => $this->cdej->cluster->nom,
                ]),
            ]),
            'user' => $this->whenLoaded('user', fn() => [
                'id' => $this->user->id,
                'phone' => $this->user->phone_whatsapp,
                'role' => $this->user->role,
            ]),
            'cotisations' => $this->whenLoaded('cotisations', fn() => $this->cotisations->map(fn($c) => [
                'annee' => $c->annee,
                'montant' => $c->montant,
                'statut' => $c->statut,
                'paid_at' => $c->paid_at?->format('d/m/Y'),
            ])),
            'created_at' => $this->created_at?->format('d/m/Y'),
        ];
    }
}
