<?php

use Illuminate\Support\Facades\Route;
use Orion\Facades\Orion;
use App\Http\Controllers\Attendance\AttendanceController;
use App\Http\Controllers\Attendance\UpdateAttendanceController;
use App\Http\Controllers\Attendance\ShowAttendanceController;

Orion::resource('attendances', AttendanceController::class);
Route::post('attendances/update', [UpdateAttendanceController::class, 'update'])->name('attendances.update');
Route::get('attendances/show-chart/{user}', [ShowAttendanceController::class, 'showChart']);
//Route::get('attendances/show/{user}', [ShowAttendanceController::class, 'show']);
