<?php

use App\Http\Controllers\AttendanceController; 
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect()->route('dashboard');
});
Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');


Route::middleware(['auth','role:employee'])->group(function () {
    Route::get('/dashboard/employee', [DashboardController::class,'employee'])->name('dashboard.employee');
    Route::get('/my-tasks', [TaskController::class, 'myTasks'])->name('tasks.my');
    Route::patch('/tasks/{task}/status',[TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
    Route::get('/attendances/check-in', [AttendanceController::class, 'showCheckinForm'])->name('attendances.checkin.form');
    Route::post('/attendances/check-in', [AttendanceController::class, 'checkin'])->name('attendances.checkin');
    Route::post('/attendances/check-out', [AttendanceController::class, 'checkout'])->name('attendances.checkout');
});

Route::middleware(['auth','role:admin'])->group(function () {
    Route::get('/dashboard/admin', [DashboardController::class,'admin'])->name('dashboard.admin');
    Route::resource('departments', DepartmentController::class);
    Route::resource('employees', EmployeeController::class);
    Route::resource('tasks', TaskController::class);
    Route::get('/attendance/reports', [AttendanceController::class, 'reports'])->name('attendances.reports');
});
Route::resource('attendances', AttendanceController::class);



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
