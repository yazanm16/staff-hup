<?php

use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Support\Facades\Route;
//her we can define api routes
// login route
Route::post('login', [AuthController::class, 'login']);
Route::middleware(['auth:sanctum', 'check_token_expiration'])->name('api.')->group(function () { 
    Route::get('me', [AuthController::class, 'me']);
    Route::get('/profile', [ProfileController::class, 'edit']);
    Route::patch('/profile', [ProfileController::class, 'update']);
    Route::patch('/change-password', [ProfileController::class, 'changePassword']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::resource('tasks.comments', CommentController::class)->only([
        'index', 'store', 'destroy','update'
    ]);
});
Route::middleware(['auth:sanctum', 'check_token_expiration','role:employee'])->group(function () {
    Route::get('/dashboard/employee', [DashboardController::class,'employee']);
    Route::post('attendances/check-in',[AttendanceController::class,'checkin']);
    Route::post('attendances/check-out',[AttendanceController::class,'checkout']);
    Route::get('attendances',[AttendanceController::class,'index']);
    Route::get('/my-tasks', [TaskController::class, 'myTasks']);
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus']);

    });
Route::middleware(['auth:sanctum','check_token_expiration','role:admin'])->name('api.')->group( function () {
    Route::get('/dashboard/admin', [DashboardController::class,'admin']);
    Route::get('attendances/reports',[AttendanceController::class,'reports']);
    Route::get('attendances/export/csv', [AttendanceController::class, 'exportCsv']);
    Route::get('attendances/export/xlsx', [AttendanceController::class, 'exportExcel']);
    Route::post('attendances/import', [AttendanceController::class, 'import']);
    Route::resource('employee', EmployeeController::class)->except(['show', 'create']);
    Route::resource('department',DepartmentController::class)->except(['create','show']);
    Route::post('comments/{id}/restore', [CommentController::class, 'restore']);
    Route::delete('comments/{id}/force-delete', [CommentController::class, 'forceDelete']);
    Route::get('tasks/{task}/comments/deleted', [CommentController::class, 'deleted']);
    Route::resource('tasks', TaskController::class)->except(['show', 'create', 'edit']);

    
});
Route::middleware(['auth:sanctum','check_token_expiration','permission:role.manage'])->name('api.')->group(function () {
    Route::resource('roles', RoleController::class)->except(['show', 'create', 'edit']);
    Route::resource('permissions', PermissionController::class)->except('show', 'create', 'edit');

});

    
