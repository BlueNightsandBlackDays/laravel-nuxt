<?php

use Orion\Facades\Orion;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\Role\StoreRoleController;

Orion::resource('roles', RoleController::class);
Route::post('roles/create', [StoreRoleController::class, 'store']);
