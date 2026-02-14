<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;

class WeeklyAttendanceBatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public function handle()
    {
        Bus::batch([
            new GenerateWeeklyReportJob(),
            new DetectAttendanceIssuesJob(),
        ])
            ->then(function () {
                dispatch(new NotifyManagersJob());
            })
            ->catch(function ($e) {
                logger()->error('Weekly Attendance Batch Failed', [
                    'error' => $e->getMessage(),
                ]);
            })
            ->finally(function () {
                logger()->info('Weekly Attendance Batch Finished');
            })
            ->name('Weekly Attendance Report')
            ->dispatch();
    }

}
