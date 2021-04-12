<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'name', 'description'
    ];

    public function chapters(){
        return $this->hasMany(Chapter::class)->orderBy('order');
    }

    public function userCourseGroup(){
        return $this->hasOne(UserCourseGroup::class);
    }

    public function userCourseGroups(){
        return $this->hasMany(UserCourseGroup::class);
    }

    public function groupCourses(){
        return $this->hasMany(GroupCourse::class);
    }

    public function groupCourse(){
        return $this->hasOne(GroupCourse::class);
    }

    public function exercises(){
        return $this->hasManyThrough(Exercise::class, Chapter::class);
    }
}
