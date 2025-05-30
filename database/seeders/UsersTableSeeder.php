<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::updateOrCreate([
            'name' => 'Admin',
            'email' => 'admin@demo.com',
            'password' => Hash::make('demo123'),
        ]);

        $user->assignRole('Admin');
    }
}
