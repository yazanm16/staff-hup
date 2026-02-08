<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\User;
use App\Services\AttendanceService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\HttpCache\Esi;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(protected AttendanceService $attendanceService)
    {
        
    }
    public function index()
    {
        
        $data = $this->attendanceService->getAttendancesForIndex();
        
        return view('attendances.index',$data);
    }

 
    public function showCheckinForm()
    {
        return view('attendances.checkin');
    }

    public function checkIn()
    {
        try{
            $this->attendanceService->checkIn();
            return redirect()->route('attendances.index')->with('message', 'You checked in now.')->with('type', 'success');

        }catch(Exception $e){
            return redirect()
                ->route('attendances.index')
                ->with('message', $e->getMessage())
                ->with('type', 'warning');
        }
        
    }
    public function checkout(){
        try{
            $result = $this->attendanceService->checkOut();
            return redirect()->route('attendances.index')->with('message', 'Checked out at ' . $result['check_out_at'] . ' — Hours: ' . $result['hours'])->with('type', 'success');
        }catch(Exception $e){

            return redirect()->route('attendances.index')->with('message', $e->getMessage())->with('type', 'waring');
        } 

    }

    public function reports(Request $request)
    {
        $data=$this->attendanceService->buildReport($request->all());
        $filters = $this->attendanceService->getReportFilters();
        

        return view('attendances.report', array_merge($data,$filters));
    }

    public function exportXlsx(Request $request)
    {
        // $from = $request->input('from', now()->subDays(7)->toDateString());
        // $to = $request->input('to', now()->toDateString());

        // $csv=$this->attendanceService->exportCsv($from, $to);

        // return response($csv)
        //     ->header('Content-Type', 'text/csv; charset=UTF-8')
        //     ->header('Content-Disposition', "attachment; filename=attendance_report_{$from}_to_{$to}.csv");
        $from = $request->query('from', now()->subDays(7)->toDateString());
        $to= $request->query('to', now()->toDateString());

        return $this->attendanceService->exportXlsx($from, $to);
    }
}