<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function show(Request $request, $product)
    {
        $resource = Product::where('sku', $product)->orWhere('id', $product);

        if ($request->has('status')) {
            $resource = $resource->where('status', $request->status);
        }

        $resource = $resource->firstOrFail();
        return response()->json([
            'data' => $resource,
        ]);
    }
}
