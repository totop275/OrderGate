<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Laptop Pro X1',
                'sku' => 'LP-X1-2024',
                'price' => 1299.99,
                'stock' => 50,
                'status' => Product::STATUS_ACTIVE
            ],
            [
                'name' => 'Wireless Mouse',
                'sku' => 'WM-100',
                'price' => 29.99,
                'stock' => 200,
                'status' => Product::STATUS_ACTIVE
            ],
            [
                'name' => 'Mechanical Keyboard',
                'sku' => 'KB-MECH-01',
                'price' => 89.99,
                'stock' => 75,
                'status' => Product::STATUS_ACTIVE
            ],
            [
                'name' => '4K Monitor',
                'sku' => 'MON-4K-27',
                'price' => 399.99,
                'stock' => 30,
                'status' => Product::STATUS_ACTIVE
            ],
            [
                'name' => 'USB-C Hub',
                'sku' => 'HUB-USB-C',
                'price' => 49.99,
                'stock' => 100,
                'status' => Product::STATUS_ACTIVE
            ],
            [
                'name' => 'Gaming Headset',
                'sku' => 'HS-GAME-01',
                'price' => 79.99,
                'stock' => 45,
                'status' => Product::STATUS_ACTIVE
            ],
            [
                'name' => 'External SSD 1TB',
                'sku' => 'SSD-EXT-1TB',
                'price' => 129.99,
                'stock' => 60,
                'status' => Product::STATUS_ACTIVE
            ],
            [
                'name' => 'Webcam HD',
                'sku' => 'CAM-HD-01',
                'price' => 59.99,
                'stock' => 80,
                'status' => Product::STATUS_ACTIVE
            ],
            [
                'name' => 'Wireless Earbuds',
                'sku' => 'EAR-WL-01',
                'price' => 99.99,
                'stock' => 120,
                'status' => Product::STATUS_ACTIVE
            ],
            [
                'name' => 'Laptop Stand',
                'sku' => 'LS-AL-01',
                'price' => 34.99,
                'stock' => 150,
                'status' => Product::STATUS_ACTIVE
            ],
            [
                'name' => 'Bluetooth Speaker',
                'sku' => 'SPK-BT-01',
                'price' => 69.99,
                'stock' => 85,
                'status' => Product::STATUS_ACTIVE
            ],
            [
                'name' => 'Smart Watch',
                'sku' => 'WCH-SM-01',
                'price' => 199.99,
                'stock' => 40,
                'status' => Product::STATUS_ACTIVE
            ],
            [
                'name' => 'Tablet Pro',
                'sku' => 'TAB-PRO-01',
                'price' => 499.99,
                'stock' => 25,
                'status' => Product::STATUS_ACTIVE
            ],
            [
                'name' => 'Wireless Charger',
                'sku' => 'CHG-WL-01',
                'price' => 39.99,
                'stock' => 110,
                'status' => Product::STATUS_ACTIVE
            ],
            [
                'name' => 'Noise Cancelling Headphones',
                'sku' => 'HP-NC-01',
                'price' => 249.99,
                'stock' => 35,
                'status' => Product::STATUS_ACTIVE
            ],
            [
                'name' => 'Gaming Mouse Pad',
                'sku' => 'MP-GAME-01',
                'price' => 24.99,
                'stock' => 200,
                'status' => Product::STATUS_ACTIVE
            ],
            [
                'name' => 'USB Flash Drive 128GB',
                'sku' => 'USB-128G',
                'price' => 19.99,
                'stock' => 180,
                'status' => Product::STATUS_ACTIVE
            ],
            [
                'name' => 'Portable Power Bank',
                'sku' => 'PB-20000',
                'price' => 45.99,
                'stock' => 90,
                'status' => Product::STATUS_ACTIVE
            ],
            [
                'name' => 'RGB Keyboard',
                'sku' => 'KB-RGB-01',
                'price' => 119.99,
                'stock' => 55,
                'status' => Product::STATUS_ACTIVE
            ],
            [
                'name' => 'RGB Keyboard 2',
                'sku' => 'KB-RGB-02',
                'price' => 129.99,
                'stock' => 55,
                'status' => Product::STATUS_ACTIVE
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['sku' => $product['sku']],
                $product
            );
        }
    }
}
