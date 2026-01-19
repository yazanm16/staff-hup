<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Comment;
use App\Policies\CommentPolicy;
use App\Repositories\Contracts\DepartmentRepositoryContract;
use App\Repositories\Contracts\EmployeeRepositoryContract;
use App\Repositories\DepartmentRepository;
use App\Repositories\EmployeeRepository;


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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
