<?php

namespace App\Http\Controllers\User;

use Orion\Http\Controllers\RelationController;
use App\Models\User;

class UserRolesController extends RelationController
{
    protected $model = User::class;
    protected $relation = 'roles';
}
