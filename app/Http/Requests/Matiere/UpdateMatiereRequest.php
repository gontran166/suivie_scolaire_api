<?php

namespace App\Http\Requests\Matiere;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMatiereRequest extends FormRequest
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

            'coefficient' => [
                'sometimes',
                'integer',
                'min:1'
            ],

            'classe_id' => [
                'sometimes',
                'exists:classes,id'
            ],
        ];
    }
}