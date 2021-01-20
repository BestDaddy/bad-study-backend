<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $fillable = [
        'course_id', 'name', 'description', 'order'
    ];

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function exercises(){
        return $this->hasMany(Exercise::class)->orderBy('order');
    }
}
