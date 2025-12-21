<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use function Symfony\Component\Clock\now;

class DashboardController extends Controller
{
    public function index(){
        return view('dashboard.homePage');
    }

    public function admin (){
        $users=User::get();
        $tasks=Task::get();
        $completedTasks = Task::where('status','Completed')->orderBy('updated_at','desc')->take(6)->get();
        $completed=$tasks->where('status','Completed')->count();
        $pendingTasks=$tasks->where('status','Pending')->count();
        $inProgressTasks = $tasks->where('status','In-Progress')->count();
        $todayPresent=Attendance::whereDate('date',Carbon::today())->whereNotNull('check_in')->count();
        $totalTask = $tasks->count();
        $completedPercent = 0;
        $inProgressPercent = 0;
        $pendingPercent = 0;
        if ($totalTask) {
            $completedPercent = round(($completed / $totalTask) * 100);
            $inProgressPercent = round(($inProgressTasks / $totalTask) * 100);
            $pendingPercent = round(($pendingTasks / $totalTask) * 100);
        }
        return view('dashboard.admin',compact('users','completedTasks','inProgressTasks','todayPresent','pendingTasks','completedPercent','inProgressPercent','pendingPercent'));
    }
    public function employee (){
        $id = Auth::id();
        $myTasks = Task::where('user_id', $id)->whereDate('due_date',today())->where('status','!=','Completed')->orderBy('due_date')->get();
        $tasks = Task::where('user_id', $id)->get();
        $numTasks = $tasks->count();
        $completedTasks=$tasks->where('status','Completed')->count();
        $attendanceDays = Attendance::where('user_id', $id)->whereBetween('date', [Carbon::now()->startOfMonth()->toDateString(),Carbon::now()->endOfMonth()->toDateString()])->distinct('date')->count();
        return view('dashboard.employee', compact('myTasks','completedTasks','numTasks','attendanceDays'));
    }
}
