<?php 
namespace App\Repositories;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\User;
use App\Repositories\Contracts\AttendanceRepositoryContract;
use Illuminate\Support\Collection;

class AttendanceRepository implements AttendanceRepositoryContract{
    
    public function getOpenAttendanceForToday(int $userId): ?Attendance
    {
        return Attendance::where('user_id', $userId)
            ->whereDate('check_in', now()->toDateString())
            ->whereNull('check_out')->first();

    }
    public function getAttendanceForPeriod(int $userId, string $startDate, string $endDate):Collection
    {
        return Attendance::where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])->get();
    }
    
    public function getTodayActiveAttendance(int $userId,string $date): ?Attendance{
        return Attendance::where('user_id', $userId)
            ->where('date', $date)
            ->whereNotNull('check_in')
            ->whereNull('check_out')
            ->first();
    }
    public function createCheckIn(array $data): Attendance
    {
        return Attendance::create($data);
    }
    public function updateCheckOut(Attendance $attendance, array $data):Attendance
    {
        $attendance->update($data);
        return $attendance->refresh();
    }
    
    public function getReportBaseQuery(string $from, string $to){
        return Attendance::with(['user.department'])
            ->whereBetween('date', [$from, $to]);
    }
    public function getDepartments(){
        return Department::orderBy('name')->get();
    }
    public function getUsers()
    {
        return User::orderBy('name')->get();
    }
    public function paginateReport($query, int $perPage=8){
        return $query->orderBy('date', 'desc')->paginate($perPage)->withQueryString();
    }
    public function getAttendancesForExport(string $from, string $to)
    {
        return Attendance::with(['user.department'])
            ->whereBetween('date', [$from, $to])
            ->orderBy('date', 'desc')
            ->get();
    }
}