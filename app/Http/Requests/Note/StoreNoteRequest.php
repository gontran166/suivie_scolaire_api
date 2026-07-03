<?php

namespace App\Http\Requests\Note;

use Illuminate\Foundation\Http\FormRequest;

class StoreNoteRequest extends FormRequest {
    public function rules(): array
    {
        return [

            'eleve_id' => [
                'required',
                'exists:eleves,id'
            ],

            'matiere_id' => [
                'required',
                'exists:matieres,id'
            ],

            'note' => [
                'required',
                'numeric',
                'min:0',
                'max:20'
            ],

            'trimestre' => [
                'required',
                'in:1,2,3'
            ],

            'annee_scolaire' => [
                'required',
                'string'
            ],
        ];
    }
}