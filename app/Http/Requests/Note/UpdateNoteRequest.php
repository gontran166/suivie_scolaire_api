<?php

namespace App\Http\Requests\Note;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNoteRequest extends FormRequest
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

            'matiere_id' => [
                'sometimes',
                'exists:matieres,id'
            ],

            'note' => [
                'sometimes',
                'numeric',
                'min:0',
                'max:20'
            ],

            'trimestre' => [
                'sometimes',
                'in:1,2,3'
            ],

            'annee_scolaire' => [
                'sometimes',
                'string'
            ],
        ];
    }
}