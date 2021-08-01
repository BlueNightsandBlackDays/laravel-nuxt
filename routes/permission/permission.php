<?php

use Orion\Facades\Orion;
use App\Http\Controllers\Permission\PermissionController;

Orion::resource('permissions', PermissionController::class);
