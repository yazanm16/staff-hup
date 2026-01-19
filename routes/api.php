<?php

use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\EmployeeController;
use Illuminate\Support\Facades\Route;
//her we can define api routes
Route::resource('employee', EmployeeController::class);
Route::resource('department',DepartmentController::class);