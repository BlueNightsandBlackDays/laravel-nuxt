<?php

use Orion\Facades\Orion;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\Role\RoleUsersController;
use App\Http\Controllers\Role\RolePermissionsController;
use App\Http\Controllers\Role\RevokeRoleController;

Orion::resource('roles', RoleController::class);
Orion::morphedByManyResource('roles', 'users', RoleUsersController::class);
Orion::belongsToManyResource('roles', 'permissions', RolePermissionsController::class);
Route::post('roles/revoke/{role}/{name}', [RevokeRoleController::class, 'revoke']);
