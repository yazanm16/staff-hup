<?php
namespace App\Services;
use App\Models\Attendance;
use App\Repositories\Contracts\AttendanceRepositoryContract;
use App\Exceptions\Attendance\AlreadyCheckedInException;
use App\Exceptions\Attendance\NoActiveCheckInException;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;

class AttendanceService 
{
    public function __construct(protected AttendanceRepositoryContract $attendanceRepository)
    {
        
    }

    public function getAttendancesForIndex(){
        $userId = Auth::id();
        $startOfWeek = now()->startOfWeek(Carbon::SATURDAY)->toDateString();
        $endOfWeek   = now()->endOfWeek(Carbon::FRIDAY)->toDateString();
        return [
            'attendance' => $this->attendanceRepository->getOpenAttendanceForToday($userId),
            'attendances' => $this->attendanceRepository->getAttendanceForPeriod($userId, $startOfWeek, $endOfWeek)
        ];
    }

    public function checkIn():array{
        $user = Auth::user();
        $now = now();
        $today = $now->toDateString();
        $existing = $this->attendanceRepository->getTodayActiveAttendance($user->id, $today);
        if($existing){
            throw new AlreadyCheckedInException();
        }
        $attendance=$this->attendanceRepository->createCheckIn([
            'user_id'=>$user->id,
            'check_in'=>$now,
            'date'=>$today
        ]);
        return [
            'attendance' => $attendance,
            'message' => 'You checked in now.',
        ];

    }
    public function checkOut(){
        $user = Auth::user();
        $now = now();
        $today = $now->toDateString();
        $attendance = $this->attendanceRepository->getTodayActiveAttendance($user->id,$today );
        if(!$attendance){
            throw new NoActiveCheckInException();
        }
    $hours = round($attendance->check_in->diffInMinutes($now) / 60,2);        
    $updated = $this->attendanceRepository->updateCheckOut($attendance, [
            'check_out' => $now,
            'work_hours' => $hours
        ]);

        return [
            'attendance' => $updated,
            'hours' => $hours,
            'check_out_at' => $now->toDateString()
        ];
        

    }
    
    public function buildReport(array $filters){
        $from=$filters['from'] ?? now()->subDays(7)->toDateString();
        $to=$filters['to'] ?? now()->toDateString();

        $query=$this->attendanceRepository->getReportBaseQuery($from, $to);
        if(!empty($filters['user_id'])){
            $query->where('user_id', $filters['user_id']);
        }
        if(!empty($filters['department_id'])){
            $query->whereHas('user',fn($q)=>
                $q->where('department_id', $filters['department_id'])
            );
        }
        $attendances = $this->attendanceRepository->paginateReport(clone $query);

        $presentDays = (clone $query)
            ->whereNotNull('check_in')
            ->distinct('date')
            ->count('date');

        $lateArrivals = (clone $query)
            ->whereTime('check_in', '>', '09:00:00')
            ->count();

        $totalHours = (clone $query)->sum('work_hours');

        $avgHours = $presentDays > 0
            ? round($totalHours / $presentDays, 2)
            : 0;

        $workingDays = \Carbon\CarbonPeriod::create($from, $to)
            ->filter(fn ($date) =>
                !in_array($date->dayOfWeek, [Carbon::FRIDAY, Carbon::SATURDAY])
            )->count();

        $absentDays = max($workingDays - $presentDays, 0);

        return compact(
            'attendances',
            'from',
            'to',
            'presentDays',
            'lateArrivals',
            'avgHours',
            'absentDays'
        );
    }
    public function getReportFilters(){
        return [
            'departments' => $this->attendanceRepository->getDepartments(),
            'users' => $this->attendanceRepository->getUsers(),
        ];
    }
    public function exportCsv(string $from, string $to): string
    {
        $attendances = $this->attendanceRepository
            ->getAttendancesForExport($from, $to);

        $csv = "\xEF\xBB\xBF";
        $csv .= "User,Department,Date,Check In,Check Out,Work Hours\n";

        foreach ($attendances as $a) {
            $csv .= sprintf(
                "\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"\n",
                $a->user->name,
                optional($a->user->department)->name,
                $a->date,
                optional($a->check_in)?->format('h:i A'),
                optional($a->check_out)?->format('h:i A'),
                $a->work_hours
            );
        }

        return $csv;
    }
}