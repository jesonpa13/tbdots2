<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Create an admin account
        User::create([
            'name' => 'Admin User',   // You can customize this
            'email' => 'admin@gmail.com',   // Customize the email
            'password' => Hash::make('password12345'),  // Use a strong password
            'user_type' => 'admin',  // Set the user_type to 'admin'
        ]);
    }
}