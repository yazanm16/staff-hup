<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use App\Traits\ApiResponse;

class DashboardController extends Controller
{
    use ApiResponse;
    public function __construct(protected DashboardService $dashboardService)
    {
        
    }
    public function admin(DashboardService $service)
    {
        return $this->success(
            $service->adminDashboard(),
            'Admin dashboard data'
        );
    }

    public function employee(DashboardService $service)
    {
        return $this->success(
            $service->employeeDashboard(),
            'Employee dashboard data'
        );
    }
}