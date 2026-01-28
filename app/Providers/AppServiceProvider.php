<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Comment;
use App\Policies\CommentPolicy;
use App\Repositories\AttendanceRepository;
use App\Repositories\AuthRepository;
use App\Repositories\CommentRepository;
use App\Repositories\Contracts\AttendanceRepositoryContract;
use App\Repositories\Contracts\DepartmentRepositoryContract;
use App\Repositories\Contracts\EmployeeRepositoryContract;
use App\Repositories\DepartmentRepository;
use App\Repositories\EmployeeRepository;
use App\Repositories\Contracts\AuthRepositoryContract;
use App\Repositories\Contracts\CommentRepositoryContract;
use App\Repositories\Contracts\DashboardRepositoryContract;
use App\Repositories\Contracts\PermissionRepositoryContract;
use App\Repositories\Contracts\ProfileRepositoryContract;
use App\Repositories\Contracts\RoleRepositoryContract;
use App\Repositories\Contracts\TaskRepositoryContract;
use App\Repositories\DashboardRepository;
use App\Repositories\PermissionRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\RoleRepository;
use App\Repositories\TaskRepository;
use Illuminate\Container\Attributes\Auth;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Comment::class => CommentPolicy::class,
    ];
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            EmployeeRepositoryContract::class,
            EmployeeRepository::class
        );

        $this->app->bind(
            DepartmentRepositoryContract::class,
            DepartmentRepository::class
        );
        $this->app->bind(
            AttendanceRepositoryContract::class,
            AttendanceRepository::class
        );
        $this->app->bind(
            AuthRepositoryContract::class,
            AuthRepository::class
        );
        $this->app->bind(
            DashboardRepositoryContract::class,
            DashboardRepository::class
        );
        $this->app->bind(
            CommentRepositoryContract::class,
            CommentRepository::class
        );
        $this->app->bind(
            PermissionRepositoryContract::class,
            PermissionRepository::class
        );
        $this->app->bind(
            ProfileRepositoryContract::class,
            ProfileRepository::class
        );
        $this->app->bind(
            RoleRepositoryContract::class,
            RoleRepository::class
        );
        $this->app->bind(
            TaskRepositoryContract::class,
            TaskRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
