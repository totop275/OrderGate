<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\BaseCRUDController;
use App\Models\Customer;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CustomerApiController extends BaseCRUDController
{
    protected $model = Customer::class;

    protected function advancedFilter(Request $request, Builder $query) {
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
    }
}
