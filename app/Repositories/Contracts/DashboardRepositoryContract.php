<?php 
namespace App\Repositories\Contracts;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Collection;

interface DashboardRepositoryContract
{
    public function getAllUsers():Collection;
    public function getAllTasks():Collection;
    public function getCompletedTasks(int $limit=6):?Collection;
    public function countTodayAttendance(): int;
    public function getUserTasks(int $userId):Collection;
    public function countUserAttendanceDays(int $userId, string $from, string $to):int;



}