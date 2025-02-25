<?php

namespace Tests\Feature;

use App\Http\Response\ApiResponse;
use App\Models\Tenant;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexReturnsAllTenants()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $property = Property::factory()->create();
        Tenant::factory()->count(3)->create([
            'property_id' => $property->id
        ]);

        $response = $this->getJson('/api/tenants');

        $data = $response->json('data');
        $this->assertGreaterThanOrEqual(3, count($data));
    }

    public function testCreateTenant()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $property = Property::factory()->create();

        $data = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '555-123-098',
            'property_id' => $property->id,
            'rent_percentage' => 20,
        ];

        $response = $this->postJson('/api/tenants', $data);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'Success',
                'message' => config('messages.create_tenant_success'),
                'data' => [
                    [
                        'name' => 'John Doe',
                        'email' => 'john.doe@example.com',
                        'phone' => '555-123-098',
                        'property_id' => $property->id,
                        'rent_percentage' => 20,
                    ]
                ]
            ]);
    }

    public function testStoreTenantValidationFails()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $data = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '555-123-098',
            'property_id' => 999,
            'rent_percentage' => 30,
        ];

        $response = $this->postJson('/api/tenants', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['property_id']);
    }

    public function testCreateTenantWithExceedingRentPercentage()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $property = Property::factory()->create();
        Tenant::factory()->create([
            'property_id' => $property->id,
            'rent_percentage' => 90,
        ]);

        $data = [
            'name' => 'Jane Doe',
            'email' => 'jane.doe@example.com',
            'phone' => '555-569-076',
            'property_id' => $property->id,
            'rent_percentage' => 20,
        ];

        $response = $this->postJson('/api/tenants', $data);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The sum of rent percentages for this property cannot exceed 100.',
            ]);
    }

    public function testDestroyTenant()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $tenant = Tenant::factory()->create();

        $response = $this->deleteJson('/api/tenants/' . $tenant->id);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'Success',
                'message' => config('messages.delete_tenant_success'),
                'data' => []
            ]);
    }



    public function testDeleteNonExistentTenant()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->deleteJson('/api/tenants/9999');

        $response->assertStatus(404)
            ->assertJsonFragment([
                'error_code' => ApiResponse::HTTP_NOT_FOUND,
                'message' => ApiResponse::NOT_FOUND,
            ]);
    }

    public function testGetMonthlyRent()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $property = Property::factory()->create();
        $tenant1 = Tenant::factory()->create(['property_id' => $property->id, 'rent_percentage' => 50]);
        $tenant2 = Tenant::factory()->create(['property_id' => $property->id, 'rent_percentage' => 50]);

        $response = $this->getJson('/api/tenants/monthly-rent?tenant_ids[]=' . $tenant1->id . '&tenant_ids[]=' . $tenant2->id);

        $expectedRentShare1 = ($property->rent_amount * 50) / 100;
        $expectedRentShare2 = ($property->rent_amount * 50) / 100;

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'Success',
                'message' => 'Success',
                'data' => [
                    [
                        'tenant_id' => $tenant1->id,
                        'monthly_rent' => $expectedRentShare1
                    ],
                    [
                        'tenant_id' => $tenant2->id,
                        'monthly_rent' => $expectedRentShare2
                    ]
                ]
            ]);
    }

    public function testGetMonthlyRentValidationFails()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/tenants/monthly-rent?tenant_ids[]=9999&tenant_ids[]=10000');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['tenant_ids.0', 'tenant_ids.1']);
    }
}
