<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\BaseCRUDController;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderApiController extends BaseCRUDController
{
    protected $model = Order::class;

    protected function advancedFilter(Request $request, Builder $query) {
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('order_date', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('order_date', '<=', $request->end_date);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_date' => 'sometimes|nullable|date:Y-m-d',
            'products' => 'required|array|min:1',
        ]);

        if ($request->has('order_date')) {
            $validated['order_date'] = date('Y-m-d', strtotime($validated['order_date']));
        } else {
            $validated['order_date'] = date('Y-m-d');
        }

        $products = Product::whereIn('id', array_keys($validated['products']))->get()->keyBy('id');
        if ($products->count() !== count(array_keys($validated['products']))) {
            return response()->json([
                'message' => 'Some products are not found',
            ], 422);
        }
        
        DB::beginTransaction();

        try {
            $order = Order::create([
                'customer_id' => $validated['customer_id'],
                'order_number' => 'ORD' . date('YmdHis') . rand(100, 999),
                'order_date' => $validated['order_date'],
                'status' => Order::STATUS_NEW,
                'total_amount' => 0,
                'created_by' => $request->user()->id,
            ]);
    
            $total = 0;
            foreach ($validated['products'] as $productId => $quantity) {
                if (!$quantity || $quantity < 1) {
                    continue;
                }

                $product = $products[$productId];
                if ($product->stock < $quantity) {
                    return response()->json([
                        'message' => 'Product stock is not enough',
                    ], 422);
                }

                $price = $product->price;
                $itemTotal = $price * $quantity;
                
                $order->orderProducts()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $itemTotal,
                ]);

                $product->stock -= $quantity;
                $product->save();
                
                $total += $itemTotal;
            }
    
            $order->update(['total_amount' => $total]);

            $order->load('orderProducts');
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }

        return response()->json([
            'data' => $order,
            'message' => 'Order created successfully.',
        ]);
    }

    public function show($resource)
    {
        $result = Order::where('order_number', $resource)->orWhere('id', $resource)->firstOrFail();
        $result->load('orderProducts.product', 'customer', 'creator', 'verifier');

        return response()->json([
            'data' => $result,
        ]);
    }

    public function update(Request $request, $order)
    {
        $order = Order::where('order_number', $order)->orWhere('id', $order)->firstOrFail();
        if ($order->status !== Order::STATUS_NEW) {
            return response()->json([
                'message' => 'Order is not updatable.',
            ], 422);
        }

        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', [Order::STATUS_COMPLETED, Order::STATUS_CANCELLED]),
        ]);

        $order->update([
            'status' => $validated['status'],
            'verified_by' => $request->user()->id,
            'verified_at' => now(),
        ]);

        if ($validated['status'] === Order::STATUS_CANCELLED) {
            $order->load('orderProducts.product');
            $order->orderProducts()->each(function ($orderProduct) {
                $orderProduct->product->stock += $orderProduct->quantity;
                $orderProduct->product->save();
            });
        }

        return response()->json([
            'message' => 'Order ' . $validated['status'] . ' successfully.',
        ]);
    }
}
