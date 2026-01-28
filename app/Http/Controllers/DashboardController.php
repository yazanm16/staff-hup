<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Task;
use App\Models\User;
use App\Services\DashboardService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use function Symfony\Component\Clock\now;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $dashboardService)
    {
        
    }
    public function index(){
        return view('dashboard.homePage');
    }

    public function admin (){
        $data = $this->dashboardService->adminDashboard();
        return view('dashboard.admin',$data);
    }
    public function employee()
    {
        $data = $this->dashboardService->employeeDashboard();
        return view('dashboard.employee', $data);
    }
}
