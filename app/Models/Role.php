<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    const ADMIN_ID = 1;
    const STUDENT_ID = 2;
    const TEACHER_ID = 3;

    public $timestamps = false;

    protected $fillable = [
        'name'
    ];
}
