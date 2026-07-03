<?php

namespace App\Http\Requests\Paiement;

use Illuminate\Foundation\Http\FormRequest;

class StorePaiementRequest extends FormRequest {
    public function rules(): array
    {
        return [

            'eleve_id' => [
                'required',
                'exists:eleves,id'
            ],

            'montant' => [
                'required',
                'numeric',
                'min:0'
            ],

            'date_paiement' => [
                'required',
                'date'
            ],

            'observations' => [
                'nullable',
                'string'
            ],
        ];
    }
}