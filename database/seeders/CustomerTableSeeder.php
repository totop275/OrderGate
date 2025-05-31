<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'name' => 'Frank Zuckenberg',
                'email' => 'frank.zuckenberg@example.com',
                'phone' => '+6281234567890'
            ],
            [
                'name' => 'Elon Tusk',
                'email' => 'elon.tusk@example.com',
                'phone' => '+6281234567891'
            ],
            [
                'name' => 'Steve Jobsless',
                'email' => 'steve.jobsless@example.com',
                'phone' => '+6281234567892'
            ],
            [
                'name' => 'Smitty Werbenjagermanjensen',
                'email' => 'smitty.werbenjagermanjensen@example.com',
                'phone' => '+6281234567893'
            ],
            [
                'name' => 'Donald Zuramp',
                'email' => 'donald.zuramp@example.com',
                'phone' => '+6281234567894'
            ]
        ];

        foreach ($customers as $customer) {
            Customer::updateOrCreate(
                ['email' => $customer['email']],
                $customer
            );
        }
    }
}
