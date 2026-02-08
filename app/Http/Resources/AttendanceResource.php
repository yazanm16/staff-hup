<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource{
    public function toArray(Request $request):array
    {
        return [
            'id' => $this->id,
            'user_id'=>$this->user_id,
            'user_name'=>$this->user->name,
            'check_in' => optional($this->check_in)->format('h:i:s'),
            'check_out' => optional($this->check_out)->format('h:i:s'),
            'work_hours'=>$this->work_hours,
            'date'=>$this->date,

        ];
    }
}