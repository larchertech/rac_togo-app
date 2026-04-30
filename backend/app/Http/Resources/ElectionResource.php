<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ElectionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'niveau' => $this->niveau,
            'statut' => $this->statut,
            'ouverture_candidatures' => $this->ouverture_candidatures?->format('d/m/Y H:i'),
            'cloture_candidatures' => $this->cloture_candidatures?->format('d/m/Y H:i'),
            'date_vote' => $this->date_vote?->format('d/m/Y'),
            'heure_ouverture_vote' => $this->heure_ouverture_vote?->format('H:i'),
            'heure_cloture_vote' => $this->heure_cloture_vote?->format('H:i'),
            'mode_scrutin' => $this->mode_scrutin,
            'postes' => $this->postes,
            'est_ouverte' => $this->estOuverte(),
            'taux_participation' => $this->tauxParticipation(),
            'proclame_at' => $this->proclame_at?->format('d/m/Y H:i'),
            'commission' => $this->whenLoaded('commission', fn() => [
                'id' => $this->commission->id,
                'type' => $this->commission->type,
                'niveau' => $this->commission->niveau,
            ]),
            'created_at' => $this->created_at?->format('d/m/Y'),
        ];
    }
}
