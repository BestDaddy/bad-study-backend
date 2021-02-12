<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'user_id', 'schedule_id', 'score', 'value', 'status'
    ];

    public function  user(){
        return $this->belongsTo(User::class);
    }

    public function schedule(){
        return $this->belongsTo(Schedule::class);
    }
}
