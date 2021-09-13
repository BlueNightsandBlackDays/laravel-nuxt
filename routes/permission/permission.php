<?php

use Orion\Facades\Orion;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Permission\PermissionController;
use App\Http\Controllers\Permission\RevokePermissionController;
use App\Http\Controllers\Permission\FetchPermissions;

Orion::resource('permissions', PermissionController::class);
Route::post('permissions/revoke/{role}/{name}', [RevokePermissionController::class, 'revoke']);
Route::get('permissions/fetchPermissions/{user}', [FetchPermissions::class, 'show']);
