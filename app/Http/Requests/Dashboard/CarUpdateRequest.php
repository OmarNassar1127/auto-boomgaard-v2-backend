<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class CarUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Basic Information (all optional for updates)
            'brand' => ['sometimes', 'string', 'max:255'],
            'model' => ['sometimes', 'string', 'max:255'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'tax_info' => ['sometimes', 'string', 'max:255'],
            'mileage' => ['sometimes', 'integer', 'min:0'],
            'year' => ['sometimes', 'integer', 'min:1900', 'max:' . (date('Y') + 2)],
            'color' => ['sometimes', 'string', 'max:255'],
            'transmission' => ['sometimes', 'string', 'max:255'],
            'fuel' => ['sometimes', 'string', 'max:255'],
            'power' => ['sometimes', 'integer', 'min:0'],
            
            // JSON fields (optional)
            'specifications' => ['sometimes', 'array'],
            'highlights' => ['sometimes', 'array'],
            'options_accessories' => ['sometimes', 'array'],
            'options_accessories.data' => ['sometimes', 'array'],
            'options_accessories.data.exterieur' => ['sometimes', 'array'],
            'options_accessories.data.infotainment' => ['sometimes', 'array'],
            'options_accessories.data.interieur_comfort' => ['sometimes', 'array'],
            'options_accessories.data.extra' => ['sometimes', 'array'],
            
            // Status fields
            'vehicle_status' => ['sometimes', 'in:sold,listed,reserved,upcoming'],
            'post_status' => ['sometimes', 'in:draft,published'],
        ];
    }
}
