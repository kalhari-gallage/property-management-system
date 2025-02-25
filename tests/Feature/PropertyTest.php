<?php

namespace Tests\Feature;

use App\Http\Response\ApiResponse;
use App\Models\Property;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexReturnsAllProperties()
    {
        Property::factory()->count(3)->create();
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;
        $response = $this->getJson('/api/properties', [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function testIndexWithFilters()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;
        Property::factory()->create([
            'name' => 'Sunrise Apartments',
            'address' => '123 Sunrise St.',
            'owner_id' => $user->id,
            'rent_amount' => 1200,
        ]);
        Property::factory()->create([
            'name' => 'Sunset Apartments',
            'address' => '456 Sunset Ave.',
            'owner_id' => $user->id,
            'rent_amount' => 1500,
        ]);

        $response = $this->getJson('/api/properties?name=Sunrise&min_rent=1000', [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['name' => 'Sunrise Apartments']);
    }

    public function testStoreProperty()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $data = [
            'name' => 'Green Park Villas',
            'address' => '789 Green Park St.',
            'rent_amount' => 2000,
            'owner_id' => $user->id,
        ];

        $response = $this->postJson('/api/properties', $data);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'Success',
                'message' => 'Property created successfully.',
                'data' => [
                    [
                        'id' => 6,
                        'name' => 'Green Park Villas',
                        'address' => '789 Green Park St.',
                        'rent_amount' => 2000,
                        'owner_id' => 6
                    ]
                ]
            ]);
    }

    public function testShowProperty()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $property = Property::factory()->create(['owner_id' => $user->id]);

        $response = $this->getJson('/api/properties/' . $property->id);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => $property->name,
                'address' => $property->address,
            ]);
    }

    public function testShowPropertyNotFound()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/properties/9999');

        $response->assertStatus(404)
            ->assertJsonFragment([
                'status' => ApiResponse::ERROR_STATUS,
                'message' => ApiResponse::NOT_FOUND,
            ]);
    }

    public function testUpdateProperty()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $property = Property::factory()->create(['owner_id' => $user->id]);

        $data = [
            'name' => 'Updated Property Name',
            'address' => 'Updated Address',
            'rent_amount' => 2500,
        ];

        $response = $this->putJson('/api/properties/' . $property->id, $data);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Updated Property Name',
                'address' => 'Updated Address',
                'rent_amount' => 2500,
            ]);
    }

    public function testUpdatePropertyNotFound()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $data = [
            'name' => 'Non-existent Property',
            'address' => 'Non-existent Address',
            'rent_amount' => 3000,
        ];
        // Check with non-existent property ID
        $response = $this->putJson('/api/properties/9999', $data);

        $response->assertStatus(404)
            ->assertJsonFragment([
                'status' => ApiResponse::ERROR_STATUS,
                'message' => ApiResponse::NOT_FOUND,
            ]);
    }

    public function testDestroyProperty()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $property = Property::factory()->create(['owner_id' => $user->id]);

        $response = $this->deleteJson('/api/properties/' . $property->id);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'Success',
                'message' => config('messages.delete_property_success'),
                'data' => []
            ]);
    }

    public function testDestroyPropertyNotFound()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $response = $this->deleteJson('/api/properties/9999');

        $response->assertStatus(404)
            ->assertJsonFragment([
                'status' => ApiResponse::ERROR_STATUS,
                'message' => ApiResponse::NOT_FOUND,
            ]);
    }
    public function testRentDistribution()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        // Create a property and tenants
        $property = Property::factory()->create();
        $tenant1 = Tenant::factory()->create(['property_id' => $property->id, 'rent_percentage' => 50]);
        $tenant2 = Tenant::factory()->create(['property_id' => $property->id, 'rent_percentage' => 50]);

        // Send GET request to rent-distribution endpoint
        $response = $this->getJson('/api/properties/' . $property->id . '/rent-distribution');

        // Assert status and structure of the response
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'Success',
                'message' => 'Success',
                'data' => [
                    'property_name' => $property->name,
                    'total_rent' => $property->rent_amount,
                    'tenants' => [
                        [
                            'tenant_name' => $tenant1->name,
                            'rent_share' => round(($property->rent_amount * 50) / 100, 2),
                            'late_fee' => 0,
                        ],
                        [
                            'tenant_name' => $tenant2->name,
                            'rent_share' => round(($property->rent_amount * 50) / 100, 2),
                            'late_fee' => 0,
                        ]
                    ]
                ]

            ]);
    }

    public function testRentDistributionNoTenants()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $property = Property::factory()->create(['owner_id' => $user->id]);

        $response = $this->getJson('/api/properties/' . $property->id . '/rent-distribution');

        $response->assertStatus(404)
            ->assertJsonFragment([
                'status' => ApiResponse::ERROR_STATUS,
                'message' => config('messages.no_tenants_for_property'),
            ]);
    }
}
