<?php
namespace App\Jobs;

use App\Models\Attendance;
use App\Services\AttendanceService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Batchable;

class DetectAttendanceIssuesJob implements ShouldQueue
{
    use Batchable;
    public function handle(AttendanceService $attendanceService)

    {
        $data = cache()->get('Weekly Report',collect());
        $issues = $attendanceService->detectIssues($data);
        cache()->put('Attendance Issues', $issues,now()->addHours(2));
        

    }
}