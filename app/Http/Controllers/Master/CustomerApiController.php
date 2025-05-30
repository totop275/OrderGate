<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();
        
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('phone')) {
            $query->where('phone', $request->phone);
        }

        if ($request->has('email')) {
            $query->where('email', $request->email);
        }

        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        $result = $query->paginate($request->per_page ?? 10);
        return response()->json([
            'data' => $result,
        ]);
    }
}
