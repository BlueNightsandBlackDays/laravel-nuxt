<?php

namespace App\Http\Controllers\Role;

use Orion\Http\Controllers\RelationController;
use App\Models\Role;

class RoleUsersController extends RelationController
{
    protected $model = Role::class;
    protected $relation = 'users';
}
