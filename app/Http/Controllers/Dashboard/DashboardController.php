<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $apiData = app(DashboardApiController::class)->dashboard()['data'];
        
        return view('dashboard', $apiData);
    }
}
