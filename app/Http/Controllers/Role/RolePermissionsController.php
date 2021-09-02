<?php

namespace App\Http\Controllers\Role;

use Orion\Http\Controllers\RelationController;
use App\Models\Role;

class RolePermissionsController extends RelationController
{
    protected $model = Role::class;
    protected $relation = 'permissions';
}
