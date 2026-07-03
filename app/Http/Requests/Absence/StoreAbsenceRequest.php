<?php

namespace App\Http\Requests\Absence;

use Illuminate\Foundation\Http\FormRequest;

class StoreAbsenceRequest extends FormRequest {
    public function rules(): array
    {
        return [

            'eleve_id' => [
                'required',
                'exists:eleves,id'
            ],

            'date_absence' => [
                'required',
                'date'
            ],

            'motif' => [
                'nullable',
                'string'
            ],

            'statut' => [
                'in:justifiee,non justifiee,en attente'
            ],
        ];
    }
}