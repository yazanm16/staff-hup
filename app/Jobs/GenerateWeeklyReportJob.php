<?php
namespace App\Jobs;
use App\Exports\AttendanceReportExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\AttendanceService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Batchable;
use Illuminate\Support\Facades\Log;

class GenerateWeeklyReportJob implements ShouldQueue
{
    use Batchable;

    public function handle(AttendanceService $attendanceService)
    {
        try {
            $from = now()->subWeek()->startOfWeek();
            $to = now()->subWeek()->endOfWeek();

            Log::info('GenerateWeeklyReportJob: Generating report', [
                'from' => $from->toDateString(),
                'to' => $to->toDateString()
            ]);

            $data = $attendanceService->generateReport($from, $to);

            Log::info('GenerateWeeklyReportJob: Data retrieved', [
                'count' => $data->count()
            ]);

            $fileName = 'reports/weekly_attendance_' . now()->subWeek()->format('Y_W') . '.xlsx';
            Excel::store(new AttendanceReportExport($data), $fileName);

            Log::info('GenerateWeeklyReportJob: Report saved', [
                'file' => $fileName
            ]);

            cache()->put('Weekly Report', $data, now()->addHours(24));

            Log::info('GenerateWeeklyReportJob: Completed successfully');

        } catch (\Exception $e) {
            Log::error('GenerateWeeklyReportJob: Failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}