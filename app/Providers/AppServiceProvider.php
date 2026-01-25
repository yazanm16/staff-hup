<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Comment;
use App\Policies\CommentPolicy;
use App\Repositories\AttendanceRepository;
use App\Repositories\AuthRepository;
use App\Repositories\Contracts\AttendanceRepositoryContract;
use App\Repositories\Contracts\DepartmentRepositoryContract;
use App\Repositories\Contracts\EmployeeRepositoryContract;
use App\Repositories\DepartmentRepository;
use App\Repositories\EmployeeRepository;
use App\Repositories\Contracts\AuthRepositoryContract;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
