<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExerciseResult extends Model
{
    protected $fillable = [
        'user_id', 'exercise_id', 'score', 'value', 'comment', 'checked_at', 'status'
    ];

    const STATUS_ON_CHECKING = 0;
    const STATUS_PASSED = 1;

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function exercise(){
        return $this->belongsTo(Exercise::class);
    }

    public function attachments(){
        return $this->morphMany(Attachment::class, 'model');
    }

    public static function getStatusTexts() {
        return [
            ExerciseResult::STATUS_ON_CHECKING => [
                'code' => ExerciseResult::STATUS_ON_CHECKING,
                'text' => 'На проверке',
                'color'=> 'warning'
            ],
            ExerciseResult::STATUS_PASSED => [
                'code' => ExerciseResult::STATUS_PASSED,
                'text' => 'Сдано',
                'color'=> 'success'
            ],
        ];
    }
}
