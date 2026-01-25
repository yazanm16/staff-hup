<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\AttendanceResource;
use App\Models\Attendance;
use App\Services\AttendanceService;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;

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
        try{
            $result = $this->attendanceService->checkIn();
            return $this->success([
                'attendance' => new AttendanceResource($result['attendance'])
            ], $result['message'], 201);
        }catch(Exception $e){
            return $this->error($e->getMessage(), 409);
        }
    }

    public function checkOut(){
        try{
            $result = $this->attendanceService->checkOut();
            return $this->success([
                'attendance' => new AttendanceResource($result['attendance']),
                'hours' => $result['hours']
            ], 'Checked out successfully');
        }catch(Exception $e){
            return $this->error($e->getMessage(), 409);
        }
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

}