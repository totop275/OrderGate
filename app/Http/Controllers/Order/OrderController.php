<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $query = Order::with('customer');
            $cb = fn ($fn) => $fn;

            return DataTables::of($query)
                ->addColumn('action', function ($order) use ($cb) {
                    return <<<HTML
                        <div class="d-flex gap-2">
                            <a href="{$cb(route('orders.edit', $order->id))}" class="btn btn-primary btn-sm" title="Edit">
                                <i class="bx bx-edit"></i>
                            </a>
                            <button class="btn btn-danger btn-sm delete-btn" title="Delete" data-id="{$order->id}">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                    HTML;
                })
                ->make(true);
        }

        return view('order.index');
    }

    public function create()
    {
        $customers = Customer::all();
        $products = Product::where('status', Product::STATUS_ACTIVE)->get();
        return view('order.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_date' => 'required|date',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'status' => 'required|string|in:pending,processing,completed,cancelled'
        ]);

        $order = Order::create([
            'customer_id' => $validated['customer_id'],
            'order_number' => 'ORD' . date('YmdHis') . rand(100, 999),
            'order_date' => $validated['order_date'],
            'status' => $validated['status'],
            'total_amount' => 0
        ]);

        $total = 0;
        foreach ($validated['products'] as $item) {
            $product = Product::find($item['product_id']);
            $price = $product->price;
            $quantity = $item['quantity'];
            $itemTotal = $price * $quantity;
            
            $order->products()->attach($product->id, [
                'quantity' => $quantity,
                'price' => $price,
                'total' => $itemTotal
            ]);
            
            $total += $itemTotal;
        }

        $order->update(['total_amount' => $total]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Order created successfully.',
            ]);
        }

        return redirect()->route('orders.index')
            ->with('success', 'Order created successfully.');
    }

    public function destroy(Order $order)
    {
        $order->products()->detach();
        $order->delete();

        return response()->json([
            'message' => 'Order deleted successfully.',
        ]);
    }
}
