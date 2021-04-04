<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'user_id', 'schedule_id', 'score', 'value', 'status'
    ];

    const STATUS_PENDING = 0;
    const STATUS_GOING = 2;
    const STATUS_EXERCISES_IN_PROCESS = 3;
    const STATUS_EXERCISES_SUBMITTED = 4;
    const STATUS_PASSED = 5;
    const STATUS_MISSED = 6;

    public function  user(){
        return $this->belongsTo(User::class);
    }

    public function schedule(){
        return $this->belongsTo(Schedule::class);
    }

    public static function getStatusTexts() {
        return [
            self::STATUS_PENDING => [
                'code' => self::STATUS_PENDING,
                'text' => 'Ожидается',
                'color'=> 'default'
            ],
            self::STATUS_GOING => [
                'code' => self::STATUS_GOING,
                'text' => 'Идет урок',
                'color'=> 'pizdec'
            ],
            self::STATUS_EXERCISES_IN_PROCESS => [
                'code' => self::STATUS_EXERCISES_IN_PROCESS,
                'text' => 'ДЗ не сдан',
                'color'=> 'pizdec'
            ],
            self::STATUS_EXERCISES_SUBMITTED => [
                'code' => self::STATUS_EXERCISES_SUBMITTED,
                'text' => 'ДЗ на проверке',
                'color'=> 'warning'
            ],
            self::STATUS_PASSED => [
                'code' => self::STATUS_PASSED,
                'text' => 'Пройдено',
                'color'=> 'success'
            ],
            self::STATUS_MISSED => [
                'code' => self::STATUS_MISSED,
                'text' => 'Пропущено',
                'color'=> 'disabled'
            ],
        ];
    }
}
