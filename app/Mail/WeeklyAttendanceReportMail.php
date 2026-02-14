<?php
namespace App\Mail;
use Illuminate\Mail\Mailable;
class WeeklyAttendanceReportMail extends Mailable
{
     public function __construct(private string $fileName)
    {
    }
    public function build()
    {
        return $this->subject('Weekly Attendance Report')
                    ->attach(storage_path('app/reports/' . $this->fileName))
                    ->view('emails.weekly-attendance');
    }
}
