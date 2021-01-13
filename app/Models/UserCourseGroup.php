<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class UserCourseGroup extends Model
{
    protected $table = 'user_course_group';

    protected $fillable = [
        'user_id', 'course_id', 'group_id', 'score', 'status'
    ];


}
