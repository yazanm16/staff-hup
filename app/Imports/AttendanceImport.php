<?php
namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

use Maatwebsite\Excel\Concerns\WithHeadingRow;


class AttendanceImport implements ToCollection,WithHeadingRow
{
    protected Collection $rows;
    public function collection(Collection $rows)
    {
        Validator::make($rows->toArray(), [
            '*.user_id'   => 'required|integer',
            '*.date'      => 'nullable',
            '*.check_in'  => 'nullable',
            '*.check_out' => 'nullable',
        ])->validate();
        $this->rows = $rows;
    }
    public function rows(): Collection
    {
        return $this->rows;
    }
}