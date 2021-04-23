<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExerciseResult extends Model
{
    protected $fillable = [
        'user_id', 'exercise_id', 'score', 'value', 'comment', 'checked_at'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function exercise(){
        return $this->belongsTo(Exercise::class);
    }

    public function attachments(){
        return $this->morphMany(Attachment::class, 'model');
    }
}
