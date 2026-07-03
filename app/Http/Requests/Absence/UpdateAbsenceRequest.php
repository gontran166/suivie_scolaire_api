<?php

namespace App\Http\Requests\Absence;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAbsenceRequest extends FormRequest
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

            'date_absence' => [
                'sometimes',
                'date'
            ],

            'motif' => [
                'nullable',
                'string'
            ],

            'statut' => [
                'sometimes',
                'in:justifiee,non justifiee,en attente'
            ],
        ];
    }
}