<?php

namespace App\Http\Requests\Eleve;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEleveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'nom' => [
                'sometimes',
                'string',
                'max:100'
            ],

            'prenom' => [
                'sometimes',
                'string',
                'max:100'
            ],

            'date_naissance' => [
                'sometimes',
                'date'
            ],

            'photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'
            ],

            'nom_parent' => [
                'nullable',
                'string'
            ],

            'telephone_parent' => [
                'nullable',
                'string'
            ],

            'classe_id' => [
                'sometimes',
                'exists:classes,id'
            ],

            'user_id' => [
                'nullable',
                'exists:users,id'
            ],
        ];
    }
}