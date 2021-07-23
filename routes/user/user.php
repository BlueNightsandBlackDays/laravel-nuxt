<?php

use Illuminate\Support\Facades\Route;
use Orion\Facades\Orion;
use App\Http\Controllers\User\UserController;

Route::group(['as' => 'api.'], function() {
    Orion::resource('users', UserController::class);
});
