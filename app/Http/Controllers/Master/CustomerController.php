<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:customers.browse')->only(['index']);
        $this->middleware('can:customers.create')->only(['create', 'store']);
        $this->middleware('can:customers.update')->only(['edit', 'update']);
        $this->middleware('can:customers.delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $query = Customer::query();
            $cb = fn ($fn) => $fn;

            return DataTables::of($query)
                ->addColumn('action', function ($customer) use ($cb) {
                    return <<<HTML
                        <div class="d-flex gap-2">
                            <a href="{$cb(route('customers.edit', $customer->id))}" class="btn btn-primary btn-sm" title="Edit">
                                <i class="bx bx-edit"></i>
                            </a>
                            <button class="btn btn-danger btn-sm delete-btn" title="Delete" data-id="{$customer->id}">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                    HTML;
                })
                ->make(true);
        }

        return view('master.customer.index');
    }

    public function create()
    {
        $activeSidebar = 'customers.index';
        return view('master.customer.create', compact('activeSidebar'));
    }

    public function store(Request $request)
    {
        $apiResponse = (new CustomerApiController)->store($request);

        return redirect()->route('customers.index')
            ->with('success', $apiResponse['message']);
    }

    public function edit(Customer $customer)
    {
        $activeSidebar = 'customers.index';
        return view('master.customer.edit', compact('customer', 'activeSidebar'));
    }

    public function update(Request $request, $customer)
    {
        $apiResponse = (new CustomerApiController)->update($request, $customer);

        return redirect()->route('customers.index')
            ->with('success', $apiResponse['message']);
    }

    public function destroy($customer)
    {
        $apiResponse = (new CustomerApiController)->destroy($customer);

        return $apiResponse;
    }
}
