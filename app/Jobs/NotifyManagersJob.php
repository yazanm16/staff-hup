<?php
namespace App\Jobs;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Notification;
use App\Notifications\WeeklyAttendanceNotification;
use Illuminate\Support\Facades\Mail;
use App\Mail\WeeklyAttendanceReportMail;
use App\Models\User;

class NotifyManagersJob implements ShouldQueue
{
    public function handle()
    
    {
        logger()->info('NotifyManagersJob started');
        $admins=User::role('admin')->get();
        $issues = cache()->get('Attendance Issues');
        Notification::send($admins, new WeeklyAttendanceNotification($issues));
        $fileName = 'weekly_attendance_' . now()->subWeek()->format('Y_W') . '.xlsx';
        Mail::to($admins->pluck('email'))->queue(new WeeklyAttendanceReportMail($fileName));
        
        
    }
}