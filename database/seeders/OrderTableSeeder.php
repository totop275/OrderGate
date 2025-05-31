<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;

class OrderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orderToGenerate = 1000;

        $orderDates = [];
        for ($i = 0; $i < $orderToGenerate; $i++) {
            $randomDay = rand(0, 365);
            $orderDates[] = date('Y-m-d', strtotime("-$randomDay days"));
        }

        sort($orderDates);

        $customers = Customer::all();
        $products = Product::all();
        $users = User::all();

        foreach ($orderDates as $orderDate) {
            $productCount = rand(1, 5);
            $orderProductObjs = $products->random($productCount);
            $totalAmount = 0;
            $orderProducts = [];
            foreach ($orderProductObjs as $product) {
                $quantity = rand(1, 5);
                $totalAmount += $product->price * $quantity;
                $orderProducts[] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                    'total' => $product->price * $quantity,
                ];
            }

            if ($orderDate < date('Y-m-d', strtotime('-1 day'))) {
                $status = [Order::STATUS_COMPLETED, Order::STATUS_CANCELLED][[0, 0, 1, 0][rand(0, 3)]];
            } else {
                $status = Order::STATUS_NEW;
            }

            $verifyDate = date('Y-m-d H:i:s', strtotime($orderDate . ' +' . rand(0, 86399) . ' seconds'));

            $order = Order::create([
                'customer_id' => $customers->random()->id,
                'order_date' => $orderDate,
                'order_number' => 'ORD' . date('YmdHis', strtotime($verifyDate)) . rand(100, 999),
                'total_amount' => $totalAmount,
                'created_by' => $users->random()->id,
                'status' => $status,
                'verified_by' => $status !== Order::STATUS_NEW ? $users->random()->id : null,
                'verified_at' => $status !== Order::STATUS_NEW ? $verifyDate : null,
            ]);

            $order->orderProducts()->createMany($orderProducts);
        }
    }
}
