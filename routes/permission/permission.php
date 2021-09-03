<?php

use Orion\Facades\Orion;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Permission\PermissionController;
use App\Http\Controllers\Permission\ShowPermissionController;

Orion::resource('permissions', PermissionController::class);
Route::get('permissions/show/{user}', [ShowPermissionController::class, 'show']);
Route::post('permissions/revoke/{role}/{name}', [ShowPermissionController::class, 'revoke']);
