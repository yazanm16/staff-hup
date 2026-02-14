<?php

use App\Jobs\WeeklyAttendanceBatchJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::job(WeeklyAttendanceBatchJob::class)->weeklyOn(0, '08:00')->timezone('Asia/Gaza');

