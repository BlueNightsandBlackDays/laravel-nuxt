<?php

use Orion\Facades\Orion;
use App\Http\Controllers\Role\RoleController;

Orion::resource('roles', RoleController::class);
