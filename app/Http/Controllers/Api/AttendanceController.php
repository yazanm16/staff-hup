<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\AttendanceResource;
use App\Imports\AttendanceImport;
use App\Models\Attendance;
use App\Services\AttendanceService;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class AttendanceController extends Controller{

    use ApiResponse;
    
    public function __construct(protected AttendanceService $attendanceService)
    {
   
    }

    public function index(){
        $data = $this->attendanceService->getAttendancesForIndex();
        return $this->success([
            'currents_attendance' => $data['attendance'] ? new AttendanceResource($data['attendance']) : null,
            'weekly_attendances'=>AttendanceResource::collection($data['attendances'])
        ]);
    }

    public function checkIn(){
        
        $result = $this->attendanceService->checkIn();
        return $this->success([
            'attendance' => new AttendanceResource($result['attendance'])
        ], $result['message'], 201);
       
    }

    public function checkOut(){
        
        $result = $this->attendanceService->checkOut();
        return $this->success([
            'attendance' => new AttendanceResource($result['attendance']),
            'hours' => $result['hours']
        ], 'Checked out successfully');
        
    }

    public function reports(Request $request)
{
    $data = $this->attendanceService->buildReport($request->all());

    return $this->success([
        'stats' => [
            'present_days' => $data['presentDays'],
            'late_arrivals'=> $data['lateArrivals'],
            'avg_hours'    => $data['avgHours'],
            'absent_days'  => $data['absentDays'],
        ],
        'attendances' => AttendanceResource::collection(
            $data['attendances']
        )
    ]);
}

public function exportCsv(Request $request)
{
    $from = $request->query('from', now()->subDays(7)->toDateString());
    $to   = $request->query('to', now()->toDateString());

    $csv = $this->attendanceService->exportCsv($from, $to);

    return response($csv, 200, [
        'Content-Type'        => 'text/csv; charset=UTF-8',
        'Content-Disposition' => "attachment; filename=attendance_report_{$from}_to_{$to}.csv",
    ]);
}

public function exportExcel(Request $request){
    $from = $request->query('from', now()->subDays(7)->toDateString());
    $to   = $request->query('to', now()->toDateString());
    return $this->attendanceService->exportXlsx($from,$to);  
}
public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv',
        ]);

        $import = new AttendanceImport();
        Excel::import($import, $request->file('file'));

        $result = $this->attendanceService
            ->importAttendances($import->rows());
        return $this->success($result, 'Import finished');
    }


}