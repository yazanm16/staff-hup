<?php

namespace App\Jobs;

// use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Foundation\Bus\Dispatchable;
// use Illuminate\Queue\InteractsWithQueue;
// use Illuminate\Queue\SerializesModels;
// use Illuminate\Support\Facades\Bus;

// class WeeklyAttendanceBatchJob implements ShouldQueue
// {
//     use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
//     public function handle()
//     {
//         logger()->info('WeeklyAttendanceBatchJob: started');
//         Bus::batch([
//             new GenerateWeeklyReportJob(),
//             new DetectAttendanceIssuesJob(),
//         ])
//             ->then(function () {
//                 dispatch(new NotifyManagersJob());
//             })
//             ->catch(function ($e) {
//                 logger()->error('Weekly Attendance Batch Failed', [
//                     'error' => $e->getMessage(),
//                 ]);
//             })
//             ->finally(function () {
//                 logger()->info('Weekly Attendance Batch Finished');
//             })
//             ->name('Weekly Attendance Report')
//             ->dispatch();
//     }

// }

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

class WeeklyAttendanceBatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public function handle()
    {
        Log::info('WeeklyAttendanceBatchJob: started');
        
        $batch = Bus::batch([
            new GenerateWeeklyReportJob(),
            new DetectAttendanceIssuesJob(),
        ])
            ->then(function ($batch) {
                Log::info('WeeklyAttendanceBatchJob: Batch completed successfully', [
                    'batch_id' => $batch->id,
                    'name' => $batch->name,
                    'total_jobs' => $batch->totalJobs,
                    'processed_jobs' => $batch->processedJobs(),
                ]);
                
                dispatch(new NotifyManagersJob());
                
                Log::info('WeeklyAttendanceBatchJob: NotifyManagersJob dispatched');
            })
            ->catch(function ($batch, \Throwable $e) {
                Log::error('Weekly Attendance Batch Failed', [
                    'batch_id' => $batch->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            })
            ->finally(function ($batch) {
                Log::info('Weekly Attendance Batch Finished', [
                    'batch_id' => $batch->id,
                    'name' => $batch->name,
                    'total_jobs' => $batch->totalJobs,
                    'failed_jobs' => $batch->failedJobs
                ]);
            })
            ->name('Weekly Attendance Report')
            ->dispatch();
            
        Log::info('WeeklyAttendanceBatchJob: Batch dispatched', [
            'batch_id' => $batch->id
        ]);
    }
}