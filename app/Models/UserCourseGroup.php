<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class UserCourseGroup extends Model
{
    protected $table = 'user_course_group';

    protected $fillable = [
        'user_id', 'course_id', 'group_id', 'score', 'status'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function course(){
        return $this->belongsTo(Course::class);
    }
    public function group(){
        return $this->belongsTo(Group::class);
    }
}
