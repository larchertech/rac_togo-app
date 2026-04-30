<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CastVoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $election = $this->route('election');
        $postes = $election->postes ?? [];

        $rules = [];
        foreach ($postes as $poste) {
            $rules["votes.{$poste}"] = [
                'required',
                'integer',
                Rule::exists('candidatures', 'id')->where(function($q) use ($election, $poste) {
                    $q->where('election_id', $election->id)
                      ->where('poste', $poste)
                      ->where('statut', 'valide');
                })
            ];
        }
        return $rules;
    }

    public function messages(): array
    {
        return [
            'votes.*.required' => 'Vous devez voter pour chaque poste.',
            'votes.*.exists' => 'Candidat invalide pour ce poste.',
        ];
    }
}
