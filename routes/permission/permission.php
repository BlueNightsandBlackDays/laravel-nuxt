<?php

use Orion\Facades\Orion;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Permission\PermissionController;
use App\Http\Controllers\Permission\ShowPermissionController;

Orion::resource('permissions', PermissionController::class);
Route::get('permissions/show-permissions/{user}', [ShowPermissionController::class, 'showPermission']);
Route::post('permissions/revoke-permission/{role}/{name}', [ShowPermissionController::class, 'revokePermission']);
