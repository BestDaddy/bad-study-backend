<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'group_id', 'chapter_id', 'group_course_id', 'live_url', 'starts_at'
    ];


    public function group(){
        return $this->belongsTo(Group::class);
    }

    public function chapter(){
        return $this->belongsTo(Chapter::class);
    }

    public function groupCourse(){
        return $this->belongsTo(GroupCourse::class, 'group_course_id');
    }

    public function attendance(){
        return $this->hasMany(Attendance::class);
    }
}
