<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendanceReportExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct(Private Collection $attendance)
    {
    }
    public function collection()
    {
        return $this->attendance->map(function ($a) {
            return [
                'user'=>$a->user->name,
                'Department'  => optional($a->user->department)->name,
                'Date'        => $a->date,
                'Check In'    => optional($a->check_in)?->format('H:i'),
                'Check Out'   => optional($a->check_out)?->format('H:i'),
                'Work Hours'  => $a->work_hours,
            ];
        });
    }
    public function headings(): array
    {
        return [
            'User',
            'Department',
            'Date',
            'Check In',
            'Check Out',
            'Work Hours',
        ];
    }
}
