<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TenantSeeder extends Seeder
{
    public function run()
    {
        // Get all properties
        $properties = Property::all();

        // Define unique tenant names
        $tenantNames = [
            'Alice Smith',
            'Bob Johnson',
            'Charlie Brown',
            'Diana Prince',
            'Eve Wilson',
            'Frank Moore',
            'Grace Taylor',
            'Henry Clark',
            'Ivy Adams',
            'Jack White',
            'Karen Lee',
            'Liam Harris',
            'Mia Martinez',
            'Noah Garcia',
            'Olivia Lopez',
            'Paul Scott',
            'Quinn Green',
            'Rachel Hall',
            'Samuel Young',
            'Tina King',
            'Uma Nelson',
            'Victor Perez',
            'Wendy Carter',
            'Xander Evans',
            'Yara Rivera',
            'Zack Torres',
            'Anna Baker',
            'Ben Wright',
            'Cathy Hill',
            'Dylan Flores',
            'Ella Murphy',
            'Finn Collins',
            'Gina Reed',
            'Hank Morgan',
            'Isla Brooks',
            'Jake Bennett',
            'Kara Wood',
            'Leo Coleman',
            'Maya Hughes',
            'Nina Price',
            'Oscar Sanders',
            'Penny Bryant',
            'Quincy Russell',
            'Riley Griffin',
            'Sofia Diaz',
            'Tyler Myers',
            'Ursula Ford',
            'Violet Hayes',
            'Wyatt Chavez',
            'Xena Guzman',
            'Yvonne Tucker',
            'Zane Potter',
            'Ava Simmons',
            'Blake Foster',
            'Cora Gibson',
            'Dexter Mendoza',
            'Elsie Silva',
            'Felix Vega',
            'Gwen Rios',
            'Hugo Estrada',
            'Ivy Ortega',
            'Jasper Vargas',
            'Kira Cortez',
            'Landon Norton',
            'Mila Buchanan',
            'Nolan Jimenez',
            'Olive Phelps',
            'Parker Mullins',
            'Quinn Daniels',
            'Rory Robbins',
            'Sawyer Curtis',
            'Tessa Black',
            'Ulysses Wilkins',
            'Vera Rowe',
            'Wade Lyons',
            'Xyla Cross',
            'Yara Graves',
            'Zeke Stone',
            'Avery Fletcher',
            'Brielle Jensen',
            'Cruz Parsons',
            'Dakota Reeves',
            'Emery Francis',
            'Fernando Gardner',
            'Gemma Simpson',
            'Harvey Lane',
            'Isabelle Harper',
            'Jett Fuller',
            'Kara Perkins',
            'Luca Walsh',
            'Maren Love',
            'Nash Joseph',
            'Opal Chandler',
            'Peyton Blair',
            'Quincy Hogan',
            'Remy Cohen',
            'Sienna Morales',
            'Tucker Snyder',
            'Uma Norman',
            'Vance Figueroa',
            'Willa Poole',
            'Xander Swanson',
        ];

        // Create 3 tenants for each property
        foreach ($properties as $index => $property) {
            for ($i = 0; $i < 3; $i++) {
                $tenantIndex = ($index * 3) + $i;

                Tenant::create([
                    'name' => $tenantNames[$tenantIndex],
                    'email' => Str::slug($tenantNames[$tenantIndex]) . '_' . Str::random(5) . '@example.com',
                    'phone' => '555-' . mt_rand(100, 999) . '-' . mt_rand(1000, 9999),
                    'property_id' => $property->id,
                    'rent_percentage' => $i === 0 ? 50 : ($i === 1 ? 40 : 10),
                ]);
            }
        }
    }
}
