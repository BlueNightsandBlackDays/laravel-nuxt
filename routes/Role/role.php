<?php

use Orion\Facades\Orion;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\Role\RoleUsersController;
use App\Http\Controllers\Role\RolePermissionsController;
use App\Http\Controllers\Role\ShowRoleController;

Orion::resource('roles', RoleController::class);
Orion::morphedByManyResource('roles', 'users', RoleUsersController::class);
Orion::belongsToManyResource('roles', 'permissions', RolePermissionsController::class);
Route::get('roles/show-roles/{user}', [ShowRoleController::class, 'showRole']);
Route::post('roles/revoke-role/{role}/{name}', [ShowRoleController::class, 'revokeRole']);
