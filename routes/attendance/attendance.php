<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Attendance\AttendanceController;
use App\Http\Controllers\Attendance\ViewAttendanceController;

Route::resource('attendances', AttendanceController::class);
Route::post('attendances/update-current', [AttendanceController::class, 'updateCurrent'])->name('attendances.updateCurrent');
Route::get('attendances/show/{user}', [ViewAttendanceController::class, 'show']);
Route::delete('attendances/delete/{user}', [ViewAttendanceController::class, 'destroy']);
