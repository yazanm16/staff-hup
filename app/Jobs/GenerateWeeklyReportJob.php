<?php
namespace App\Jobs;
use App\Exports\AttendanceReportExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\AttendanceService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Batchable;
class GenerateWeeklyReportJob implements ShouldQueue
{
    use Batchable;
    public function handle(AttendanceService $attendanceService)
    {
        $from=now()->subWeek()->startOfWeek();
        $to=now()->subWeek()->endOfWeek();
        $data=$attendanceService->generateReport($from,$to);
        $fileName = 'reports/weekly_attendance_' . now()->subWeek()->format('Y_W') . '.xlsx';
        Excel::store(new AttendanceReportExport($data),$fileName);
        cache()->put('Weekly Report', $data);
    }
}