<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PropertyRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'rent_amount' => 'required|numeric',
            'owner_id' => 'required|exists:users,id',
        ];

        // If the request is a PUT/PATCH request (for update), make all fields optional (except for rules where required)
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            // Use 'sometimes' to allow updating without modifying fields
            $rules['name'] = 'sometimes|string|max:255';
            $rules['address'] = 'sometimes|string|max:255';
            $rules['rent_amount'] = 'sometimes|numeric';
            $rules['owner_id'] = 'sometimes|exists:users,id';
        }

        return $rules;
    }

    /**
     * Custom messages for validation errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The property name is required.',
            'name.string' => 'The property name must be a valid string.',
            'name.max' => 'The property name cannot exceed 255 characters.',
            'address.required' => 'The property address is required.',
            'address.string' => 'The property address must be a valid string.',
            'address.max' => 'The property address cannot exceed 255 characters.',
            'rent_amount.required' => 'The rent amount is required.',
            'rent_amount.numeric' => 'The rent amount must be a numeric value.',
            'owner_id.required' => 'The owner ID is required.',
            'owner_id.exists' => 'The selected owner ID is invalid.',
        ];
    }
}
