<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $query = Order::with('customer')->select('orders.*');
            $cb = fn ($fn) => $fn;

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            return DataTables::of($query)
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

    public function show($order)
    {
        $order = Order::where('order_number', $order)->with('customer', 'orderProducts.product')->first();
        if (!$order) {
            abort(404);
        }

        $activeSidebar = 'orders.index';

        return view('order.show', compact('order', 'activeSidebar'));
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
