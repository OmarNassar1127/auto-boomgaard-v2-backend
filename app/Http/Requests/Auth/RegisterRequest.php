<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:255',
                'min:2'
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email'
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed'
            ],
            'role' => [
                'sometimes',
                'string',
                Rule::in(['verkoper']) // Only allow verkoper role for registration
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
            'name.required' => 'Naam is verplicht.',
            'name.min' => 'Naam moet minimaal 2 karakters bevatten.',
            'name.max' => 'Naam mag maximaal 255 karakters bevatten.',
            'email.required' => 'E-mailadres is verplicht.',
            'email.email' => 'E-mailadres moet een geldig e-mailadres zijn.',
            'email.unique' => 'Dit e-mailadres is al in gebruik.',
            'email.max' => 'E-mailadres mag maximaal 255 karakters bevatten.',
            'password.required' => 'Wachtwoord is verplicht.',
            'password.min' => 'Wachtwoord moet minimaal 8 karakters bevatten.',
            'password.confirmed' => 'Wachtwoord bevestiging komt niet overeen.',
            'role.in' => 'De geselecteerde rol is ongeldig.'
        ];
    }
}
