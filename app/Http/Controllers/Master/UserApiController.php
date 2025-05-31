<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\BaseCRUDController;
use App\Models\User;

class UserApiController extends BaseCRUDController
{
    protected $model = User::class;
}
