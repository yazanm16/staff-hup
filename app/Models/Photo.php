<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = ['path','disk'];
    

    public function photoable() {
        return $this->morphTo();
    }
}
