<?php
namespace App\Repositories;

use App\Models\Attendance;
use App\Models\Task;
use App\Models\User;
use App\Repositories\Contracts\DashboardRepositoryContract;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DashboardRepository implements DashboardRepositoryContract
{
    public function getAllUsers():Collection
    {
        return User::get();
    }

    public function getAllTasks():Collection
    {
        return Task::get();
    }

    public function getCompletedTasks(int $limit = 6): ?Collection
    {
        return Task::where('status', 'Completed')
            ->orderBy('updated_at', 'desc')
            ->take($limit)->get();
    }

    public function countTodayAttendance(): int
    {
        return Attendance::whereDate('date', Carbon::today())
            ->whereNotNull('check_in')->count();
    }

    public function getUserTasks(int $userId): Collection
    {
        return Task::where('user_id', $userId)->get();
    }

    public function countUserAttendanceDays(int $userId, string $from, string $to): int
    {
        return Attendance::where('user_id', $userId)
            ->whereBetween('date', [$from, $to])
            ->distinct('date')->count();
    }
}