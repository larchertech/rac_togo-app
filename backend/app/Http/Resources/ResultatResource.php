<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResultatResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nb_voix' => $this->nb_voix,
            'candidature' => $this->whenLoaded('candidature', fn() => [
                'id' => $this->candidature->id,
                'poste' => $this->candidature->poste,
                'alumni' => [
                    'id' => $this->candidature->alumni?->id,
                    'nom' => $this->candidature->alumni?->nom,
                    'prenom' => $this->candidature->alumni?->prenom,
                    'nom_complet' => $this->candidature->alumni?->nom_complet,
                ],
            ]),
        ];
    }
}
