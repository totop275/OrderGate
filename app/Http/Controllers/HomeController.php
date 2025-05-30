<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class HomeController extends Controller
{
    public function landing()
    {
        return redirect()->route(Gate::allows('dashboard') ? 'dashboard' : 'orders.index');
    }
}
