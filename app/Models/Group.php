<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
         'name', 'description', 'chat'
    ];

    public function courses(){
        return $this->belongsToMany(Course::class, 'group_course');
    }

    public function groupCourses(){
        return $this->hasMany(GroupCourse::class);
    }

    public function users(){
        return $this->belongsToMany(User::class, 'user_course_group');
    }
}
