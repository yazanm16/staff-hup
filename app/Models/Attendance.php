<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
    'user_id',
    'check_in',
    'check_out',
    'work_hours',
    'date'
];
    public function user(){
        return $this->belongsTo(User::class);
    }
}
