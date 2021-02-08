<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupCourse extends Model
{
    protected $table = 'group_course';

    protected $fillable = [
        'group_id', 'course_id', 'teacher_id'
    ];

    public function group(){
        return $this->belongsTo(Group::class);
    }

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function teacher(){
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function schedules(){
        return $this->hasMany(Schedule::class, 'group_course_id');
    }
}
