<?php

namespace App\Http\Requests\Annonce;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnnonceRequest extends FormRequest {
    public function rules(): array
    {
        return [

            'titre' => [
                'required',
                'string',
                'max:255'
            ],

            'contenu' => [
                'required',
                'string'
            ],

            'type' => [
                'required',
                'string'
            ],

            'classe_id' => [
                'nullable',
                'exists:classes,id'
            ],

            'active' => [
                'boolean'
            ],

            'date_expiration' => [
                'nullable',
                'date'
            ],
        ];
    }
}