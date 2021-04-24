<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'role_id'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role(){
        return $this->belongsTo(Role::class);
    }

    public function isAdmin()
    {
        return $this->role_id == Role::ADMIN_ID;
    }

    public function isTeacher()
    {
        return $this->role_id == Role::TEACHER_ID;
    }

    public function fullName(){
        return $this->first_name . ' ' . $this->last_name;
    }

    public function exerciseResults(){
        return $this->hasMany(ExerciseResult::class, 'user_id');
    }

    public function courses(){
        return $this->belongsToMany(Course::class, 'user_course_group')->withPivot('score', 'status');
    }

    public function userCourseGroups(){
        return $this->hasMany(UserCourseGroup::class);
    }

    public function userCourseGroup(){
        return $this->hasOne(UserCourseGroup::class);
    }

    public function teacherGroupCourses(){
        return $this->hasMany(GroupCourse::class, 'teacher_id');
    }

    public function teacherGroups(){
        return $this->belongsToMany(Group::class, GroupCourse::class, 'teacher_id')->distinct();
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
