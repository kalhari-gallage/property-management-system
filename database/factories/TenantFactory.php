<?php

namespace Database\Factories;

use App\Models\Tenant;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'property_id' => Property::factory(),
            'rent_percentage' => $this->faker->numberBetween(1, 100),
            'late_fee' => $this->faker->randomFloat(2, 10, 100),
        ];
    }
}
