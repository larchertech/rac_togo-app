<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCandidatureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'poste' => 'required|in:president,vice_president,sg,tresorier,conseiller',
            'lettre_motivation' => 'required|string|min:200|max:2000',
            'documents' => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            'poste.required' => 'Le poste est obligatoire.',
            'lettre_motivation.required' => 'La lettre de motivation est obligatoire.',
            'lettre_motivation.min' => 'La lettre de motivation doit faire au moins 200 caractères.',
            'lettre_motivation.max' => 'La lettre de motivation ne doit pas dépasser 2000 caractères.',
        ];
    }
}
