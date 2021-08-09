<?php

use Orion\Facades\Orion;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\Role\StoreRoleController;
use App\Http\Controllers\Role\ShowRoleController;

Orion::resource('roles', RoleController::class);
Route::post('roles/create', [StoreRoleController::class, 'store']);
Route::get('roles/show-roles/{user}', [ShowRoleController::class, 'showRole']);
Route::get('roles/show-permissions/{user}', [ShowRoleController::class, 'showPermission']);
Route::patch('roles/update/{role}', [ShowRoleController::class, 'update']);
Route::post('roles/revoke-permission/{role}/{name}', [ShowRoleController::class, 'revokePermission']);
Route::post('roles/revoke-role/{role}/{name}', [ShowRoleController::class, 'revokeRole']);
