<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupCourse extends Model
{
    protected $table = 'group_course';

    protected $fillable = [
        'name', 'description', 'chat'
    ];
}
