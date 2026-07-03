<?php

namespace App\Http\Requests\Annonce;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAnnonceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'titre' => [
                'sometimes',
                'string',
                'max:255'
            ],

            'contenu' => [
                'sometimes',
                'string'
            ],

            'type' => [
                'sometimes',
                'string'
            ],

            'classe_id' => [
                'nullable',
                'exists:classes,id'
            ],

            'active' => [
                'sometimes',
                'boolean'
            ],

            'date_expiration' => [
                'nullable',
                'date'
            ],
        ];
    }
}