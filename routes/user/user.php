<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UpdatePasswordController;
//use Orion\Facades\Orion;

//Orion::resource('users', UserController::class);
Route::resource('users', UserController::class);
Route::patch('users/password/{user}', [UpdatePasswordController::class, 'update']);