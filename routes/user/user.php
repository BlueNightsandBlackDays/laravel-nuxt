<?php

use Illuminate\Support\Facades\Route;
use Orion\Facades\Orion;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserRolesController;
use App\Http\Controllers\User\UserAttendancesController;
use App\Http\Controllers\User\UpdatePasswordController;

Orion::resource('users', UserController::class);
Orion::morphToManyResource('users', 'roles', UserRolesController::class);
Orion::hasManyResource('users', 'attendances', UserAttendancesController::class);
Route::patch('users/password/{user}', [UpdatePasswordController::class, 'update']);
