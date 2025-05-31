<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardApiController extends Controller
{
    public function dashboard()
    {
        $totalOrders = Order::count();
        $totalSales = Order::sum('total_amount');
        $activeUsers = User::active()->count();
        $inactiveUsers = User::inactive()->count();
        $orderBreakdowns = [
            'New' => Order::where('status', Order::STATUS_NEW)->count(),
            'Completed' => Order::where('status', Order::STATUS_COMPLETED)->count(),
            'Cancelled' => Order::where('status', Order::STATUS_CANCELLED)->count(),
        ];

        $monthlySales = Order::select(
            DB::raw('SUBSTRING(order_date, 1, 7) as month'),
            DB::raw('SUM(total_amount) as total_sales')
        )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => $item->month,
                    'total_sales' => $item->total_sales
                ];
            })
            ->toArray();

        return ['data' => compact('totalOrders', 'totalSales', 'activeUsers', 'inactiveUsers', 'orderBreakdowns', 'monthlySales')];
    }
}
