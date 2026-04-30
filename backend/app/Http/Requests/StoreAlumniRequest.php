<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAlumniRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cdej_id' => 'required|exists:cdej,id',
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'date_naissance' => 'required|date|before:today',
            'niveau_diplome' => 'required|in:cepe,bepc,bac,bts,licence,master,formation_pro',
        ];
    }

    public function messages(): array
    {
        return [
            'cdej_id.required' => 'Le CDEJ est obligatoire.',
            'nom.required' => 'Le nom est obligatoire.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'date_naissance.required' => 'La date de naissance est obligatoire.',
            'niveau_diplome.required' => 'Le niveau de diplôme est obligatoire.',
        ];
    }
}
