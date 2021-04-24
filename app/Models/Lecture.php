<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
    protected $fillable = [
        'chapter_id', 'title', 'content', 'order'
    ];

    public function chapter(){
        return $this->belongsTo(Chapter::class);
    }
}
