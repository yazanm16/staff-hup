<?php
namespace App\Jobs;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Notification;
use App\Notifications\WeeklyAttendanceNotification;
use Illuminate\Support\Facades\Mail;
use App\Mail\WeeklyAttendanceReportMail;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class NotifyManagersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public function handle()
    {
        try {
            Log::info('NotifyManagersJob: Starting');

            $admins = User::role('admin')->get();

            Log::info('NotifyManagersJob: Admins found', [
                'count' => $admins->count()
            ]);

            if ($admins->isEmpty()) {
                Log::warning('NotifyManagersJob: No admins found to notify');
                return;
            }

            $issues = cache()->get('Attendance Issues', []);

            Log::info('NotifyManagersJob: Sending notifications', [
                'issues_count' => count($issues)
            ]);

            // Send database/broadcast notification
            Notification::send($admins, new WeeklyAttendanceNotification($issues));

            Log::info('NotifyManagersJob: Notifications sent');

            // Send email with attachment
            $fileName = 'weekly_attendance_' . now()->subWeek()->format('Y_W') . '.xlsx';
            $filePath = 'reports/' . $fileName;

            // Check if file exists before sending email
            if (Storage::exists($filePath)) {
                Log::info('NotifyManagersJob: Sending email with attachment', [
                    'file' => $fileName
                ]);
                foreach($admins as $index => $admin){
                    try{
                        $delay = $index * 15;
                        $mail=(new WeeklyAttendanceReportMail($fileName))->delay(now()->addSeconds($delay));
                        Mail::to($admin->email)->queue($mail);
                        Log::info('NotifyManagersJob: Email queued for admin', [
                            'admin_email' => $admin->email,
                            'admin_name' => $admin->name ?? 'N/A',
                            'delay_seconds' => $delay
                        ]);
                    }catch(\Exception $e) {
                        Log::error('NotifyManagersJob: Failed to send email to admin', [
                            'email' => $admin->email,
                            'error' => $e->getMessage()
                        ]);
                    }

                }
                Log::info('NotifyManagersJob: Email queued successfully');
            } else {
                Log::warning('NotifyManagersJob: Report file not found', [
                    'file' => $filePath
                ]);
            }

            Log::info('NotifyManagersJob: Completed successfully');

        } catch (\Exception $e) {
            Log::error('NotifyManagersJob: Failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}