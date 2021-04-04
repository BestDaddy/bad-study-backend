<?php


namespace App\Events;


use App\Models\ExerciseResult;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExerciseResultScored
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $exercise_result;
    public $schedule_id;

    public function __construct(ExerciseResult $exercise_result, $schedule_id)
    {
        $this->exercise_result = $exercise_result;
        $this->schedule_id = $schedule_id;
    }
}
