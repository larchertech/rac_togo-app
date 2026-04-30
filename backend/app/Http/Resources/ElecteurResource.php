<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ElecteurResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'nom_complet' => $this->nom_complet,
            'numero_membre' => $this->numero_membre,
            'niveau_diplome' => $this->niveau_diplome,
            'est_eligible' => $this->estEligibleVote(),
            'cdej' => $this->whenLoaded('cdej', fn() => [
                'id' => $this->cdej->id,
                'nom' => $this->cdej->nom,
            ]),
            'user' => $this->whenLoaded('user', fn() => [
                'id' => $this->user->id,
                'phone' => $this->user->phone_whatsapp,
            ]),
        ];
    }
}
