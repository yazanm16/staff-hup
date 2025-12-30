<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;
    protected $fillable = ['task_id','body', 'user_id', 'user_type'];

    public function user(){
        return $this->morphTo(__FUNCTION__, 'user_type', 'user_id');
    }
    public function photo(){
        return $this->morphOne(Photo::class, 'photoable');
    }

    public function task(){
        return $this->belongsTo(Task::class);
    }
}
