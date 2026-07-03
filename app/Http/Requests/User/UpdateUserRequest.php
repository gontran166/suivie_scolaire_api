<?php

namespace App\Http\Requests\User;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('id');

        return [

            'name' => [
                'sometimes',
                'string',
                'max:255'
            ],

            'email' => [
                'sometimes',
                'email',
                Rule::unique('users')
                    ->ignore($userId)
            ],

            'password' => [
                'sometimes',
                'string',
                'min:8'
            ],

            'role' => [
                'sometimes',
                'in:gestionnaire,enseignant,parent'
            ],
        ];
    }
}