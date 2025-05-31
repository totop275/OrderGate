<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\BaseCRUDController;
use App\Models\Product;

class ProductApiController extends BaseCRUDController
{
    protected $model = Product::class;

    public function show($product)
    {
        $request = request();
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
