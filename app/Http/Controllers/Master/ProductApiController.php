<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\BaseCRUDController;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductApiController extends BaseCRUDController
{
    protected $model = Product::class;
    protected $freeText = ['name'];

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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'string|max:255|unique:products,sku',
            'status' => 'string|in:' . implode(',', [Product::STATUS_ACTIVE, Product::STATUS_INACTIVE])
        ]);

        if (!$validated['sku']) {
            $validated['sku'] = 'P' . date('YmdHis') . rand(100, 999);
        }

        $product = Product::create($validated);

        return [
            'data' => $product,
            'message' => 'Product created successfully.',
        ];
    }

    public function update(Request $request, $product)
    {
        $product = Product::where('sku', $product)->orWhere('id', $product)->firstOrFail();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'string|max:255|unique:products,sku,' . $product->id,
            'status' => 'string|in:' . implode(',', [Product::STATUS_ACTIVE, Product::STATUS_INACTIVE])
        ]);

        if (!$validated['sku']) {
            unset($validated['sku']);
        }

        $product->update($validated);

        return [
            'data' => $product,
            'message' => 'Product updated successfully.',
        ];
    }

    public function destroy($product)
    {
        $product = Product::where('sku', $product)->orWhere('id', $product)->firstOrFail();

        if ($product->orderProducts()->exists()) {
            throw new \Exception('Product has been used in order, cannot be deleted.');
        }

        $product->delete();

        return [
            'message' => 'Product deleted successfully.',
        ];
    }
}
