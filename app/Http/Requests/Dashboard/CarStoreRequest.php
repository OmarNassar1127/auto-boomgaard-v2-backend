<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class CarStoreRequest extends FormRequest
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
            // Basic Information (required fields)
            'brand' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'tax_info' => ['sometimes', 'string', 'max:255'],
            'mileage' => ['required', 'integer', 'min:0'],
            'year' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 2)],
            'color' => ['required', 'string', 'max:255'],
            'transmission' => ['required', 'string', 'max:255'],
            'fuel' => ['required', 'string', 'max:255'],
            'power' => ['required', 'integer', 'min:0'],
            
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
