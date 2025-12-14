<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $id=Auth::id();
        $attendance=Attendance::where("user_id", $id)->whereDate('check_in',now()->toDateString())->whereNull('check_out')->first();
        $attendances=Attendance::where("user_id", $id)->get();
        return view('attendances.index',compact('attendance','attendances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }
    
    /**
     * Remove the specified resource from storage.
    */
    public function destroy(string $id)
    {
        //
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
            return redirect()->route('attendances.index')->with('message', 'You already checked in and not checked out yet.')->with('type','warning');
        }

        Attendance::create([
            'user_id' => $user->id,
            'check_in' => $now,
            'date' => $today,
        ]);
            return redirect()->route('attendances.index')->with('message', 'You checked in now.')->with('type','success');

        
    }
    public function checkout(Request $request){
        $user = $request->user();
        $now= Carbon::now('Asia/Gaza');
        $today = $now->toDateString();
        $attendance = Attendance::where('user_id', $user->id)->where('date', $today)->whereNotNull('check_in')->whereNull('check_out')->first();
        if(!$attendance){
            return redirect()->route('attendances.index')->with('message','No active check-in found for today.')->with('type','waring');
        }
        $attendance->check_out=$now;
        $hours = round(($attendance->check_out->timestamp - $attendance->check_in->timestamp) / 3600, 2);
        $attendance->work_hours = $hours;
        $attendance->save();
        return redirect()->route('attendances.index')->with('message','Checked out at ' . $now->toDateTimeString() . ' — Hours: ' . $hours)->with('type','success');

    }
    
}
    
//     namespace App\Http\Controllers;

// use App\Models\Attendance;
// use Illuminate\Http\Request;
// use Carbon\Carbon;

// class AttendanceController extends Controller
// {
//     // Admin: index list of attendances

//     // Employee: show check-in form/button




//     

//     // Admin report (مثال: تجميع الساعات لأسبوع محدد)
//     public function report(Request $request)
//     {
//         // استقبل تاريخ بداية ونهاية أو افتراضي آخر 7 أيام
//         $from = $request->input('from', now()->subDays(7)->toDateString());
//         $to = $request->input('to', now()->toDateString());

//         $report = Attendance::selectRaw('user_id, SUM(work_hours) as total_hours')
//             ->whereBetween('date', [$from, $to])
//             ->groupBy('user_id')
//             ->with('user')
//             ->get();

//         return view('attendance.report', compact('report','from','to'));
//     }
// }

