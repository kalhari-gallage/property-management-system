<?php

namespace App\Http\Requests;

use App\Models\Tenant;
use Illuminate\Foundation\Http\FormRequest;

class TenantRequest extends FormRequest
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
        // Base rules (common for both create and update)
        $rules = [
            'name' => 'required|string',
            'email' => 'required|email|unique:tenants,email',
            'phone' => 'required|string',
            'property_id' => 'required|exists:properties,id',
            'rent_percentage' => [
                function ($attribute, $value, $fail) {
                    $propertyId = $this->input('property_id');
                    $tenants = Tenant::where('property_id', $propertyId)->get();

                    // Ensure the rent percentage is numeric
                    if ($value !== null && !is_numeric($value)) {
                        $fail($attribute . ' must be a numeric value.');
                        return;
                    }

                    // Logic to check if other tenants have rent_percentage set
                    $tenantsWithRentPercentage = $tenants->whereNotNull('rent_percentage')->count();
                    $tenantsWithNullRentPercentage = $tenants->whereNull('rent_percentage')->count();

                    if ($tenantsWithRentPercentage > 0 && $value === null) {
                        $fail('The rent percentage is required because other tenants have specified rent percentages.');
                    }

                    if ($tenantsWithNullRentPercentage > 0 && $value !== null) {
                        $fail('The rent percentage must be null because other tenants have no rent percentage set.');
                    }

                    // Calculate the total rent percentage
                    $totalRentPercentage = $tenants->sum('rent_percentage');
                    $newRentPercentage = $value ?? 0;
                    $totalRentPercentage += $newRentPercentage;

                    if ($totalRentPercentage > 100) {
                        $fail('The sum of rent percentages for this property cannot exceed 100.');
                    }
                }
            ],
        ];

        // Additional rules for `update` request (PUT/PATCH)
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['name'] = 'sometimes|string';
            $rules['email'] = 'sometimes|email|unique:tenants,email';
            $rules['phone'] = 'sometimes|string';
            $rules['property_id'] = 'sometimes|exists:properties,id';
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
            'name.required' => 'The tenant name is required.',
            'email.required' => 'The tenant email is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email is already taken.',
            'phone.required' => 'The tenant phone is required.',
            'property_id.required' => 'The property ID is required.',
            'property_id.exists' => 'The selected property ID is invalid.',
            'rent_percentage.numeric' => 'The rent percentage must be a numeric value.',
        ];
    }
}
