<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Alice Johnson',
                'email' => 'alice@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Bob Brown',
                'email' => 'bob@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Charlie Davis',
                'email' => 'charlie@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Diana Evans',
                'email' => 'diana@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Eve Wilson',
                'email' => 'eve@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Frank Moore',
                'email' => 'frank@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Grace Taylor',
                'email' => 'grace@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Henry Clark',
                'email' => 'henry@example.com',
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
