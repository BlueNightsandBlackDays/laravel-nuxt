<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Attendance\AttendanceController;
use App\Http\Controllers\Attendance\ViewAttendanceController;

Route::resource('attendances', AttendanceController::class);
Route::get('attendances/show/{user}', [ViewAttendanceController::class, 'show']);
Route::get('attendances/show-current', [AttendanceController::class, 'showCurrent'])->name('attendances.showCurrent');
Route::post('attendances/update-current', [AttendanceController::class, 'updateCurrent'])->name('attendances.updateCurrent');
Route::delete('attendances/destroy', [AttendanceController::class, 'massDestroy'])->name('attendances.massDestroy');
