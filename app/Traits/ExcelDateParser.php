<?php

namespace App\Traits;

use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

trait ExcelDateParser
{
    protected function parseExcelDate($value): Carbon
    {
        if ($value instanceof \DateTimeInterface) {
            return Carbon::instance($value);
        }

        if (is_numeric($value)) {
            return Carbon::instance(
                ExcelDate::excelToDateTimeObject($value)
            );
        }

        return Carbon::parse($value);
    }

    protected function parseExcelTime($value, Carbon $date): ?Carbon
    {
        if (!$value) {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return Carbon::instance($value);
        }

        if (is_numeric($value)) {
            $time = ExcelDate::excelToDateTimeObject($value);

            return Carbon::parse(
                $date->toDateString().' '.$time->format('H:i:s')
            );
        }

        return Carbon::parse(
            $date->toDateString().' '.$value
        );
    }
}
