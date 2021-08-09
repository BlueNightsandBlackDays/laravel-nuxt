<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\ViewUserController;
use App\Http\Controllers\User\UpdateUserController;
//use Orion\Facades\Orion;

//Orion::resource('users', UserController::class);
Route::resource('users', UserController::class);
Route::get('users/show/{user}', [ViewUserController::class, 'show']);
Route::delete('users/delete/{user}', [ViewUserController::class, 'destroy']);
Route::patch('users/password/{user}', [UpdateUserController::class, 'updatePass']);
Route::patch('users/update/{user}', [UpdateUserController::class, 'update']);
