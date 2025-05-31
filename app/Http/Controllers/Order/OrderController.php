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
    public function __construct()
    {
        $this->middleware('can:orders.browse')->only(['index']);
        $this->middleware('can:orders.detail')->only(['show']);
        $this->middleware('can:orders.create')->only(['create', 'store']);
        $this->middleware('can:orders.update')->only(['update']);
    }

    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $query = Order::with('customer')->select('orders.*');
            $cb = fn ($fn) => $fn;

            if ($request->status) {
                if (is_array($request->status)) {
                    $query->whereIn('status', $request->status);
                } else {
                    $query->where('status', $request->status);
                }
            }

            if ($request->start_date) {
                $query->whereDate('order_date', '>=', $request->start_date);
            }

            if ($request->end_date) {
                $query->whereDate('order_date', '<=', $request->end_date);
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
