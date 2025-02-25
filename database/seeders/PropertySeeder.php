<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run()
    {
        // Get all users
        $users = User::all();

        // Define unique properties
        $properties = [
            [
                'name' => 'Sunrise Apartments',
                'address' => '123 Main St, New York, NY',
                'rent_amount' => 2000,
            ],
            [
                'name' => 'Ocean View Villa',
                'address' => '456 Beach Rd, Miami, FL',
                'rent_amount' => 3500,
            ],
            [
                'name' => 'Mountain Retreat',
                'address' => '789 Forest Ln, Denver, CO',
                'rent_amount' => 2800,
            ],
            [
                'name' => 'Golden Heights',
                'address' => '101 Skyline Dr, Los Angeles, CA',
                'rent_amount' => 4000,
            ],
            [
                'name' => 'Green Valley',
                'address' => '202 Hillside Ave, Austin, TX',
                'rent_amount' => 2500,
            ],
            [
                'name' => 'Silver Springs',
                'address' => '303 Lakeview Rd, Chicago, IL',
                'rent_amount' => 3200,
            ],
            [
                'name' => 'Royal Gardens',
                'address' => '404 Park Blvd, San Francisco, CA',
                'rent_amount' => 3800,
            ],
            [
                'name' => 'Emerald Estates',
                'address' => '505 Forest Dr, Seattle, WA',
                'rent_amount' => 3000,
            ],
            [
                'name' => 'Crystal Cove',
                'address' => '606 Ocean Dr, Miami, FL',
                'rent_amount' => 4500,
            ],
            [
                'name' => 'Sunset Hills',
                'address' => '707 Sunset Blvd, Las Vegas, NV',
                'rent_amount' => 2700,
            ],
            [
                'name' => 'Pine Grove',
                'address' => '808 Pine St, Portland, OR',
                'rent_amount' => 2200,
            ],
            [
                'name' => 'Lakeview Manor',
                'address' => '909 Lakeview Dr, Minneapolis, MN',
                'rent_amount' => 3100,
            ],
            [
                'name' => 'Harbor Lights',
                'address' => '1010 Harbor Rd, Boston, MA',
                'rent_amount' => 3600,
            ],
            [
                'name' => 'Maplewood Estates',
                'address' => '1111 Maplewood Ave, Nashville, TN',
                'rent_amount' => 2900,
            ],
            [
                'name' => 'Willow Creek',
                'address' => '1212 Willow Ln, Phoenix, AZ',
                'rent_amount' => 2400,
            ],
            [
                'name' => 'Sapphire Shores',
                'address' => '1313 Sapphire Rd, Tampa, FL',
                'rent_amount' => 3300,
            ],
            [
                'name' => 'Golden Gate Apartments',
                'address' => '1414 Golden Gate Ave, San Francisco, CA',
                'rent_amount' => 4100,
            ],
            [
                'name' => 'Redwood Retreat',
                'address' => '1515 Redwood Dr, Sacramento, CA',
                'rent_amount' => 2600,
            ],
            [
                'name' => 'Blue Ridge Villas',
                'address' => '1616 Blue Ridge Rd, Asheville, NC',
                'rent_amount' => 3400,
            ],
            [
                'name' => 'Sunflower Heights',
                'address' => '1717 Sunflower St, Kansas City, MO',
                'rent_amount' => 2300,
            ],
            [
                'name' => 'Ocean Breeze',
                'address' => '1818 Ocean Dr, Honolulu, HI',
                'rent_amount' => 5000,
            ],
            [
                'name' => 'Meadowlands',
                'address' => '1919 Meadow Ln, Dallas, TX',
                'rent_amount' => 2700,
            ],
            [
                'name' => 'Starlight Villas',
                'address' => '2020 Starlight Rd, Las Vegas, NV',
                'rent_amount' => 3900,
            ],
            [
                'name' => 'Parkside Residences',
                'address' => '2121 Parkside Ave, Chicago, IL',
                'rent_amount' => 3200,
            ],
            [
                'name' => 'Riverside Apartments',
                'address' => '2222 Riverside Dr, New Orleans, LA',
                'rent_amount' => 2800,
            ],
            [
                'name' => 'Highland Estates',
                'address' => '2323 Highland Rd, Denver, CO',
                'rent_amount' => 3700,
            ],
            [
                'name' => 'Cedarwood Villas',
                'address' => '2424 Cedarwood Ln, Seattle, WA',
                'rent_amount' => 3100,
            ],
            [
                'name' => 'Sunset Ridge',
                'address' => '2525 Sunset Blvd, Los Angeles, CA',
                'rent_amount' => 4200,
            ],
            [
                'name' => 'Maplewood Heights',
                'address' => '2626 Maplewood Ave, Portland, OR',
                'rent_amount' => 2900,
            ],
            [
                'name' => 'Oceanfront Villas',
                'address' => '2727 Ocean Dr, Miami, FL',
                'rent_amount' => 4800,
            ],
        ];

        // Assign 3 properties to each user
        foreach ($users as $index => $user) {
            for ($i = 0; $i < 3; $i++) {
                $propertyIndex = ($index * 3) + $i;
                Property::create([
                    'name' => $properties[$propertyIndex]['name'],
                    'address' => $properties[$propertyIndex]['address'],
                    'rent_amount' => $properties[$propertyIndex]['rent_amount'],
                    'owner_id' => $user->id,
                ]);
            }
        }
    }
}
