<?php

namespace App\Http\Requests\Paiement;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaiementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'eleve_id' => [
                'sometimes',
                'exists:eleves,id'
            ],

            'montant' => [
                'sometimes',
                'numeric',
                'min:0'
            ],

            'date_paiement' => [
                'sometimes',
                'date'
            ],

            'observations' => [
                'nullable',
                'string'
            ],
        ];
    }
}