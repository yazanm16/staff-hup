<?php

use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
//her we can define api routes
// login route
Route::post('login', [AuthController::class, 'login']);
Route::middleware(['auth:sanctum', 'check_token_expiration'])->group(function () { 
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
});
Route::middleware(['auth:sanctum', 'check_token_expiration','role:employee'])->group(function () {
    Route::post('attendances/check-in',[AttendanceController::class,'checkin']);
    Route::post('attendances/check-out',[AttendanceController::class,'checkout']);
    Route::get('attendances',[AttendanceController::class,'index']);
    });
Route::middleware(['auth:sanctum','check_token_expiration','role:admin'])->group( function () {
    Route::get('attendances/reports',[AttendanceController::class,'reports']);
    Route::resource('employee', EmployeeController::class);
    Route::resource('department',DepartmentController::class);
    
});
