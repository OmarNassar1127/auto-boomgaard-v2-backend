<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'current_password' => [
                'required',
                'string'
            ],
            'new_password' => [
                'required',
                'string',
                'min:8',
                'confirmed'
            ],
            'new_password_confirmation' => [
                'required',
                'string'
            ]
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'current_password.required' => 'Huidig wachtwoord is verplicht.',
            'new_password.required' => 'Nieuw wachtwoord is verplicht.',
            'new_password.min' => 'Nieuw wachtwoord moet minimaal 8 karakters bevatten.',
            'new_password.confirmed' => 'Wachtwoord bevestiging komt niet overeen.',
            'new_password_confirmation.required' => 'Wachtwoord bevestiging is verplicht.',
        ];
    }
}
