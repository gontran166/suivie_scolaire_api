<?php

namespace App\Http\Requests\Classe;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateClasseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $classeId = $this->route('id');

        return [

            'nom' => [
                'sometimes',
                'string',
                'max:100',
                Rule::unique('classes', 'nom')
                    ->ignore($classeId)
            ],

            'niveau' => [
                'sometimes',
                'in:CP1,CP2,CE1,CE2,CM1,CM2'
            ],

            'frais_scolarite' => [
                'sometimes',
                'numeric',
                'min:0'
            ],

            'annee_scolaire' => [
                'sometimes',
                'string'
            ],

            'user_id' => [
                'nullable',
                'exists:users,id'
            ],
        ];
    }
}