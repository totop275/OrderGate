<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:products.browse')->only(['index']);
        $this->middleware('can:products.create')->only(['create', 'store']);
        $this->middleware('can:products.update')->only(['edit', 'update']);
        $this->middleware('can:products.delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $query = Product::query();
            $cb = fn ($fn) => $fn;

            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

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
        return view('master.product.create', ['activeSidebar' => 'products.index']);
    }

    public function store(Request $request)
    {
        $apiResponse = (new ProductApiController)->store($request);

        return redirect()->route('products.index')
            ->with('success', $apiResponse['message']);
    }

    public function edit(Product $product)
    {
        $activeSidebar = 'products.index';
        return view('master.product.edit', compact('product', 'activeSidebar'));
    }

    public function update(Request $request, $product)
    {
        $apiResponse = (new ProductApiController)->update($request, $product);

        return redirect()->route('products.index')
            ->with('success', $apiResponse['message']);
    }

    public function destroy($product)
    {
        $apiResponse = (new ProductApiController)->destroy($product);

        return $apiResponse;
    }
}
