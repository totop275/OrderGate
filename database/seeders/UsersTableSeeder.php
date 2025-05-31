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
        $userAdmin = User::updateOrCreate([
            'email' => 'admin@demo.com',
        ],[
            'name' => 'Adminudin',
            'password' => Hash::make('demo123'),
        ]);

        $userAdmin->assignRole('Admin');

        $userStaff = User::updateOrCreate([
            'email' => 'staff@demo.com',
        ],[
            'name' => 'Staffudin',
            'password' => Hash::make('demo123'),
        ]);

        $userStaff->assignRole('Staff');
    }
}
