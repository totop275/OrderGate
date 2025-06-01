<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\BaseCRUDController;
use App\Models\Customer;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CustomerApiController extends BaseCRUDController
{
    protected $model = Customer::class;
    protected $freeText = ['name'];

    protected function advancedFilter(Request $request, Builder $query) {
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20'
        ]);
        
        $customer = Customer::create($validated);

        return [
            'data' => $customer,
            'message' => 'Customer created successfully.',
        ];
    }

    public function update(Request $request, $customer)
    {
        $customer = Customer::where('id', $customer)->firstOrFail();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20'
        ]);
        
        $customer->update($validated);

        return [
            'data' => $customer,
            'message' => 'Customer updated successfully.',
        ];
    }

    public function destroy($customer)
    {
        $customer = Customer::where('id', $customer)->firstOrFail();

        if ($customer->orders()->exists()) {
            throw new \Exception('Customer has order data, cannot be deleted.');
        }

        $customer->delete();

        return [
            'message' => 'Customer deleted successfully.',
        ];
    }
}
