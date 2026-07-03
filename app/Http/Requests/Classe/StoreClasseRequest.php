<?php

namespace App\Http\Requests\Classe;

use Illuminate\Foundation\Http\FormRequest;

class StoreClasseRequest extends FormRequest
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
                'max:100',
                'unique:classes,nom'
            ],

            'niveau' => [
                'required',
                'in:CP1,CP2,CE1,CE2,CM1,CM2'
            ],

            'frais_scolarite' => [
                'required',
                'numeric',
                'min:0'
            ],

            'annee_scolaire' => [
                'required',
                'string'
            ],

            'user_id' => [
                'nullable',
                'exists:users,id'
            ],
        ];
    }
}