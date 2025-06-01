<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class BaseCRUDController extends Controller
{
    protected $model;
    protected $freeText = [];

    public function __construct()
    {
        $pluralName = strtolower(Str::plural(class_basename($this->model)));
        $this->middleware('can:' . $pluralName . '.browse')->only(['index']);
        $this->middleware('can:' . $pluralName . '.detail')->only(['show']);
        $this->middleware('can:' . $pluralName . '.create')->only(['store']);
        $this->middleware('can:' . $pluralName . '.update')->only(['update']);
        $this->middleware('can:' . $pluralName . '.delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $query = $this->model::query();

        $this->advancedFilter($request, $query);

        $blankModel = new $this->model();
        $fillable = $blankModel->getFillable();
        $hidden = $blankModel->getHidden();

        $filterable = array_diff($fillable, $hidden);

        foreach ($filterable as $field) {
            if ($request->has($field) && $request->$field !== null) {
                if (in_array($field, $this->freeText)) {
                    $query->where($field, 'like', '%' . $request->$field . '%');
                } else {
                    $query->where($field, $request->$field);
                }
            }
        }
        
        $result = $query->paginate($request->per_page ?? 10);
        return response()->json([
            'data' => $result,
        ]);
    }

    protected function advancedFilter(Request $request, Builder $query) {}

    public function show($resource)
    {
        $result = $this->model::findOrFail($resource);

        $availableRelations = $this->model->getAvailableRelations();
        $result->load($availableRelations ?? []);

        return response()->json([
            'data' => $result,
        ]);
    }

    public function store(Request $request)
    {
        $result = $this->model::create($request->all());
        return response()->json([
            'data' => $result,
        ]);
    }
    
    public function update(Request $request, $resource)
    {
        $result = $this->model::findOrFail($resource);
        $result->update($request->all());
        return response()->json([
            'data' => $result,
        ]);
    }

    public function destroy($resource)
    {
        $result = $this->model::findOrFail($resource);
        $result->delete();
        return response()->json([
            'data' => $result,
        ]);
    }
}
