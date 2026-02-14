<?php
namespace App\Mail;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Storage;

class WeeklyAttendanceReportMail extends Mailable
{
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
