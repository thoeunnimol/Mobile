<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $customers = [
            [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'phone' => '1234567890',
                'address' => '123 Main St, City',
                'password' => Hash::make('password'),
                'is_active' => true,
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'phone' => '0987654321',
                'address' => '456 Oak Ave, Town',
                'password' => Hash::make('password'),
                'is_active' => true,
            ],
            [
                'name' => 'Bob Johnson',
                'email' => 'bob.johnson@example.com',
                'phone' => '5551234567',
                'address' => '789 Pine Rd, Village',
                'password' => Hash::make('password'),
                'is_active' => true,
            ],
            [
                'name' => 'Alice Brown',
                'email' => 'alice.brown@example.com',
                'phone' => '4449876543',
                'address' => '321 Elm St, County',
                'password' => Hash::make('password'),
                'is_active' => true,
            ],
            [
                'name' => 'Charlie Wilson',
                'email' => 'charlie.wilson@example.com',
                'phone' => '7775551234',
                'address' => '654 Maple Dr, State',
                'password' => Hash::make('password'),
                'is_active' => true,
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
} 