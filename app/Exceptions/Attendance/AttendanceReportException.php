<?php
namespace App\Exceptions\Attendance;
use App\Exceptions\BusinessException;
class AttendanceReportException extends BusinessException
{
    protected int $statusCode = 422;
    public function __construct()
    {
        parent::__construct("No attendance data found");
    }
}