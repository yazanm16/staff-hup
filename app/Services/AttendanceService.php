<?php
namespace App\Services;
use App\Exports\AttendanceReportExport;
use App\Models\Attendance;
use App\Repositories\Contracts\AttendanceRepositoryContract;
use App\Exceptions\Attendance\AlreadyCheckedInException;
use App\Exceptions\Attendance\NoActiveCheckInException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
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
    public function exportXlsx(string $from, string $to): BinaryFileResponse
    {
        $attendances = $this->attendanceRepository
            ->getAttendancesForExport($from, $to);

        return Excel::download(
            new AttendanceReportExport($attendances),
            "attendance_report_{$from}_{$to}.xlsx"
        );
    }
    public function exportCsv(string $from, string $to): string
{
    $attendances = $this->attendanceRepository
        ->getAttendancesForExport($from, $to);

    $handle = fopen('php://temp', 'r+');

    fputcsv($handle, [
        'User',
        'Department',
        'Date',
        'Check In',
        'Check Out',
        'Work Hours',
    ]);

    foreach ($attendances as $a) {
        fputcsv($handle, [
            $a->user->name,
            optional($a->user->department)->name,
            $a->date,
            optional($a->check_in)?->format('h:i:s'),
            optional($a->check_out)?->format('h:i:s'),
            $a->work_hours,
        ]);
    }

    rewind($handle);
    return stream_get_contents($handle);
    }
    public function importAttendances(Collection $rows): array
{
    $imported = 0;
    $errors = [];

    foreach ($rows as $index => $row) {
        $rowNumber = $index + 2;

        try {

            $user = $this->attendanceRepository
                ->findUser($row['user_id']);

            if (!$user) {
                throw new Exception("User not found");
            }

            $date = $this->parseExcelDate($row['date']);
            if ($date->isFuture()) {
                throw new Exception("Date is in the future");
            }

            if (
                $row['check_in'] &&
                $row['check_out'] &&
                $row['check_in'] >= $row['check_out']
            ) {
                throw new Exception("Check-in must be before check-out");
            }

            if ($this->attendanceRepository->exists(
                $user->id,
                $date->toDateString()
            )) {
                throw new Exception("Attendance already exists");
            }
            $checkIn = $this->parseExcelTime($row['check_in'], $date);

            $checkOut = $this->parseExcelTime($row['check_out'], $date);

            $hours = null;
            if ($checkIn && $checkOut) {
                $hours = round(
                $checkIn->diffInMinutes($checkOut) / 60,2);
            }

            $this->attendanceRepository->storeFromImport([
                'user_id'    => $user->id,
                'date'       => $date->toDateString(),
                'check_in'   => $checkIn,
                'check_out'  => $checkOut,
                'work_hours' => $hours,
            ]);

            $imported++;

        } catch (Exception $e) {
            $errors[] = "Row {$rowNumber}: {$e->getMessage()}";
        }
    }

    return [
        'imported' => $imported,
        'failed'   => count($errors),
        'errors'   => $errors,
    ];
    }
    private function parseExcelDate($value):Carbon
    {
        if($value instanceof \DateTimeInterface){
            return Carbon::instance($value);
        }
        if (is_numeric($value)) {
            return Carbon::instance(ExcelDate::excelToDateTimeObject($value));
        }
        return Carbon::parse($value);
    }
    private function parseExcelTime($value,$date):?Carbon
    {
        if(!$value){
            return null;
        }
        if ($value instanceof \DateTimeInterface) {
            return Carbon::instance($value);
        }
        if(is_numeric($value)){
            $time = ExcelDate::excelToDateTimeObject($value);
            return Carbon::parse($date->toDateString().' '.$time->format('H:i:s'));
        }
        return Carbon::parse($date->toDateString().' '.$value);
    }





}