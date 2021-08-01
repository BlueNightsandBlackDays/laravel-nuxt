<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Attendance\AttendanceController;

Route::resource('attendances', AttendanceController::class);
Route::get('attendances/show-current', [AttendanceController::class, 'showCurrent'])->name('attendances.showCurrent');
Route::post('attendances/update-current', [AttendanceController::class, 'updateCurrent'])->name('attendances.updateCurrent');
Route::delete('attendances/destroy', [AttendanceController::class, 'massDestroy'])->name('attendances.massDestroy');
