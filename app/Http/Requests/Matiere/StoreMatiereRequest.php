<?php

namespace App\Http\Requests\Matiere;

use Illuminate\Foundation\Http\FormRequest;

class StoreMatiereRequest extends FormRequest {
    public function rules(): array
    {
        return [

            'nom' => [
                'required',
                'string',
                'max:100'
            ],

            'coefficient' => [
                'required',
                'integer',
                'min:1'
            ],

            'classe_id' => [
                'required',
                'exists:classes,id'
            ],
        ];
    }
}