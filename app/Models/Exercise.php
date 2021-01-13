<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $fillable = [
        'chapter_id', 'name', 'content', 'order'
    ];

    public function chapter(){
        return $this->belongsTo(Chapter::class);
    }

    public function results(){
        return $this->hasMany(ExerciseResult::class);
    }

    public function result(){
        return $this->hasOne(ExerciseResult::class);
    }
}
