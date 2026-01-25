<?php
namespace App\Repositories\Contracts;
use App\Models\Attendance;

use Illuminate\Support\Collection;

interface AttendanceRepositoryContract{
    
    public function getOpenAttendanceForToday(int $userId):?Attendance;
    public function getAttendanceForPeriod(int $userId, string $startDate, string $endDate):Collection;
    public function getTodayActiveAttendance(int $userId, string $date):?Attendance;
    public function createCheckIn(array $data):Attendance;
    public function updateCheckOut(Attendance $attendance, array $data):Attendance;
    public function getReportBaseQuery(string $from, string $to);
    public function getDepartments();
    public function getUsers();
    public function paginateReport($query, int $perPage=8);
    public function getAttendancesForExport(string $from, string $to);


}