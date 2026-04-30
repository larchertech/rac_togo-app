<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CandidatureResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'numero_dossier' => $this->numero_dossier,
            'poste' => $this->poste,
            'statut' => $this->statut,
            'documents' => $this->documents,
            'lettre_motivation' => $this->lettre_motivation,
            'motif_rejet' => $this->motif_rejet,
            'valide_at' => $this->valide_at?->format('d/m/Y H:i'),
            'nb_voix' => $this->nb_voix,
            'alumni' => $this->whenLoaded('alumni', fn() => [
                'id' => $this->alumni->id,
                'nom' => $this->alumni->nom,
                'prenom' => $this->alumni->prenom,
                'nom_complet' => $this->alumni->nom_complet,
                'niveau_diplome' => $this->alumni->niveau_diplome,
                'cdej' => $this->whenLoaded('alumni.cdej', fn() => [
                    'id' => $this->alumni->cdej->id,
                    'nom' => $this->alumni->cdej->nom,
                ]),
                'user' => $this->whenLoaded('alumni.user', fn() => [
                    'id' => $this->alumni->user->id,
                    'phone' => $this->alumni->user->phone_whatsapp,
                ]),
            ]),
            'election' => $this->whenLoaded('election', fn() => [
                'id' => $this->election->id,
                'type' => $this->election->type,
                'niveau' => $this->election->niveau,
            ]),
            'validateur' => $this->whenLoaded('validateur', fn() => [
                'id' => $this->validateur->id,
                'role' => $this->validateur->role,
            ]),
            'created_at' => $this->created_at?->format('d/m/Y H:i'),
        ];
    }
}
