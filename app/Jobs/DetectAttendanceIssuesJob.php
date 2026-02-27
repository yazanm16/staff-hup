<?php
namespace App\Jobs;

use App\Models\Attendance;
use App\Services\AttendanceService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Batchable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DetectAttendanceIssuesJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(AttendanceService $attendanceService)
    {
        try {
            Log::info('DetectAttendanceIssuesJob: Starting');

            $data = cache()->get('Weekly Report', collect());

            Log::info('DetectAttendanceIssuesJob: Data retrieved from cache', [
                'count' => $data->count()
            ]);

            $issues = $attendanceService->detectIssues($data);

            Log::info('DetectAttendanceIssuesJob: Issues detected', [
                'issues_count' => count($issues)
            ]);

            cache()->put('Attendance Issues', $issues, now()->addHours(24));

            Log::info('DetectAttendanceIssuesJob: Completed successfully');

        } catch (\Exception $e) {
            Log::error('DetectAttendanceIssuesJob: Failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}