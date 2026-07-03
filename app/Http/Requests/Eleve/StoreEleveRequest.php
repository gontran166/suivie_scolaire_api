<?php

namespace App\Http\Requests\Eleve;

use Illuminate\Foundation\Http\FormRequest;

class StoreEleveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'nom' => [
                'required',
                'string',
                'max:100'
            ],

            'prenom' => [
                'required',
                'string',
                'max:100'
            ],

            'date_naissance' => [
                'required',
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
                'required',
                'exists:classes,id'
            ],

            'user_id' => [
                'nullable',
                'exists:users,id'
            ],
        ];
    }
}