<?php
namespace App\Services;
use App\Repositories\Contracts\DashboardRepositoryContract;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardService
{
    public function __construct(protected DashboardRepositoryContract $dashboardRepository)
    {
        
    }
    public function adminDashboard():array{
        $users = $this->dashboardRepository->getAllUsers();
        $tasks = $this->dashboardRepository->getAllTasks();
        $completedTask = $this->dashboardRepository->getCompletedTasks();
        $todayPresent = $this->dashboardRepository->countTodayAttendance();

        $total = $tasks->count();
        $completed = $tasks->where('status', 'Completed')->count();
        $inProgress = $tasks->where('status', 'In-Progress')->count();
        $pending = $tasks->where('status', 'Pending')->count();

        return [
            'users' => $users,
            'completedTasks' => $completedTask,
            'todayPresent' => $todayPresent,
            'inProgressTasks'=>$inProgress,
            'pendingTasks'=>$pending,
            'stats' => [
                'completedPercent' => $total ? round(($completed / $total) * 100) : 0,
                'inProgressPercent' => $total ? round(($inProgress / $total) * 100) : 0,
                'pendingPercent' => $total ? round(($pending / $total) * 100) : 0,
            ]
        ];
    }

    public function employeeDashboard():array{
        $id = Auth::id();
        $tasks = $this->dashboardRepository->getUserTasks($id);
        $todayTasks = $tasks->where('status', '!=', 'Completed')->where('due_date', today()->toDateString());
        $attendanceDays = $this->dashboardRepository->countUserAttendanceDays(
            $id,
            Carbon::now()->startOfMonth()->toDateString(),
            Carbon::now()->endOfMonth()->toDateString()
        );

        return [
            'myTasks' => $todayTasks,
            'numTasks' => $tasks->count(),
            'completedTasks' => $tasks->where('status', 'Completed')->count(),
            'attendanceDays' => $attendanceDays,
        ];
    }
}