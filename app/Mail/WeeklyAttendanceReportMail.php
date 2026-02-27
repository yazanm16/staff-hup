<?php
namespace App\Mail;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class WeeklyAttendanceReportMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public function __construct(private string $fileName)
    {
    }

    public function build()
    {
        // The file is stored in storage/app/private/reports/ by default (local disk)
        $filePath = storage_path('app/private/reports/' . $this->fileName);

        return $this->subject('Weekly Attendance Report')
                    ->attach($filePath, [
                        'as' => $this->fileName,
                        'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ])
                    ->view('emails.weekly-attendance');
    }
}
