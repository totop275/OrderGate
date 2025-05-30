<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $query = Product::query();
            $cb = fn ($fn) => $fn;

            return DataTables::of($query)
                ->addColumn('action', function ($product) use ($cb) {
                    return <<<HTML
                        <div class="d-flex gap-2">
                            <a href="{$cb(route('products.edit', $product->id))}" class="btn btn-primary btn-sm" title="Edit">
                                <i class="bx bx-edit"></i>
                            </a>
                            <button class="btn btn-danger btn-sm delete-btn" title="Delete" data-id="{$product->id}">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                    HTML;
                })
                ->make(true);
        }

        return view('master.product.index');
    }

    public function create()
    {
        return view('master.product.create');
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

        Product::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Product created successfully.',
            ]);
        }

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        return view('master.product.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
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

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Product updated successfully.',
            ]);
        }

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully.',
        ]);
    }
}
