<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\BaseCRUDController;
use Spatie\Permission\Models\Role;

class RoleApiController extends BaseCRUDController
{
    protected $model = Role::class;
}
