<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
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
        $userId = $this->route('user')->id;

        return [
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                'min:2'
            ],
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId)
            ],
            'password' => [
                'sometimes',
                'string',
                'min:8',
                'confirmed'
            ],
            'role' => [
                'sometimes',
                'required',
                'string',
                Rule::in(['admin', 'verkoper'])
            ],
            'active' => [
                'sometimes',
                'boolean'
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
            'password.min' => 'Wachtwoord moet minimaal 8 karakters bevatten.',
            'password.confirmed' => 'Wachtwoord bevestiging komt niet overeen.',
            'role.required' => 'Rol is verplicht.',
            'role.in' => 'De geselecteerde rol is ongeldig.'
        ];
    }
}
