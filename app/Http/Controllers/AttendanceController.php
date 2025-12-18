<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;


use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $startOfWeek = now()->startOfWeek(Carbon::SATURDAY);
        $endOfWeek = now()->endOfWeek(Carbon::FRIDAY);
        $id = Auth::id();
        $attendance = Attendance::where("user_id", $id)->whereDate('check_in', now()->toDateString())->whereNull('check_out')->first();
        $attendances = Attendance::where("user_id", $id)->whereBetween('date', [$startOfWeek, $endOfWeek])->get();
        return view('attendances.index', compact('attendance', 'attendances'));
    }

 
    public function showCheckinForm()
    {
        return view('attendances.checkin');
    }

    public function checkIn(Request $request)
    {
        $user = $request->user();
        $now = Carbon::now('Asia/Gaza');
        $today = $now->toDateString();
        $existing = Attendance::where('user_id', $user->id)->where('date', $today)->first();
        if ($existing && $existing->check_in && !$existing->check_out) {
            return redirect()->route('attendances.index')->with('message', 'You already checked in and not checked out yet.')->with('type', 'warning');
        }

        Attendance::create([
            'user_id' => $user->id,
            'check_in' => $now,
            'date' => $today,
        ]);
        return redirect()->route('attendances.index')->with('message', 'You checked in now.')->with('type', 'success');


    }
    public function checkout(Request $request){
        $user = $request->user();
        $now = Carbon::now('Asia/Gaza');
        $today = $now->toDateString();
        $attendance = Attendance::where('user_id', $user->id)->where('date', $today)->whereNotNull('check_in')->whereNull('check_out')->first();
        if (!$attendance) {
            return redirect()->route('attendances.index')->with('message', 'No active check-in found for today.')->with('type', 'waring');
        }
        $attendance->check_out = $now;
        $hours = round(($attendance->check_out->timestamp - $attendance->check_in->timestamp) / 3600, 2);
        $attendance->work_hours = $hours;
        $attendance->save();
        return redirect()->route('attendances.index')->with('message', 'Checked out at ' . $now->toDateTimeString() . ' — Hours: ' . $hours)->with('type', 'success');

    }

    public function reports(Request $request)
    {

        $departments = Department::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        $from = $request->input('from', Carbon::now()->subDays(7)->toDateString());
        $to = $request->input('to', Carbon::now()->toDateString());
        $user_id = $request->input('user_id');
        $department_id = $request->input('department_id');
        $baseQuery = Attendance::with(['user.department'])->whereBetween('date', [$from, $to]);

        if (!empty($user_id)) {
            $baseQuery->where('user_id', $user_id);
        }
        if (!empty($department_id)) {
            $baseQuery->whereHas('user', function ($q) use ($department_id) {
                $q->where('department_id', $department_id);
            });
        }

        $attendances = (clone $baseQuery)->orderBy('date', 'desc')->paginate(8)->withQueryString();

        $presentDays = (clone $baseQuery)->whereNotNull('check_in')->distinct('date')->count('date');

        $lateArrivals = (clone $baseQuery)->whereTime('check_in', '>', '09:00:00')->count();

        $totalHours = (clone $baseQuery)->sum('work_hours');

        $avgHours = $presentDays > 0 ? round($totalHours / $presentDays, 2) : 0;

        $period = CarbonPeriod::create($from, $to);

        $workingDays = $period->filter(function ($date) {
            return !in_array($date->dayOfWeek, [
                Carbon::FRIDAY,
                Carbon::SATURDAY
            ]);
        })->count();

        $absentDays = max($workingDays - $presentDays, 0);

        return view('attendances.report', compact(
            'attendances',
            'from',
            'to',
            'departments',
            'users',
            'presentDays',
            'lateArrivals',
            'avgHours',
            'absentDays'
        ));
    }

    public function exportCsv(Request $request)
    {
        $from = $request->input('from', Carbon::now()->subDays(7)->toDateString());
        $to = $request->input('to', Carbon::now()->toDateString());

        $attendances = Attendance::with(['user.department'])
            ->whereBetween('date', [$from, $to])
            ->orderBy('date', 'desc')
            ->get();

        $csvData = "\xEF\xBB\xBF";
        $csvData .= "User,Department,Date,Check In,Check Out,Work Hours\n";

        foreach ($attendances as $attendance) {

            $date = $attendance->date
                ? '="' . Carbon::parse($attendance->date)->format('Y-m-d') . '"'
                : '""';

            $checkIn = $attendance->check_in
                ? '="' . Carbon::parse($attendance->check_in)->format('h:i A') . '"'
                : '""';

            $checkOut = $attendance->check_out
                ? '="' . Carbon::parse($attendance->check_out)->format('h:i A') . '"'
                : '""';

            $csvData .= "\"{$attendance->user->name}\","
                . "\"{$attendance->user->department->name}\","
                . "{$date},"
                . "{$checkIn},"
                . "{$checkOut},"
                . "\"{$attendance->work_hours}\"\n";
        }

        $fileName = "attendance_report_{$from}_to_{$to}.csv";

        return response($csvData)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', "attachment; filename={$fileName}");
    }
}