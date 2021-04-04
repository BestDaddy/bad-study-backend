<?php


namespace App\Listeners;


use App\Events\ExerciseResultScored;
use App\Services\Groups\AttendancesService;
use App\Services\Groups\GroupsService;
use App\Services\Groups\SchedulesService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SyncExerciseResultScored implements ShouldQueue
{
    private $schedulesService;
    private $attendancesService;
    private $groupsService;

    public function __construct(GroupsService $groupsService, SchedulesService $schedulesService, AttendancesService $attendancesService)
    {
        $this->groupsService = $groupsService;
        $this->schedulesService = $schedulesService;
        $this->attendancesService = $attendancesService;
    }

    public function handle(ExerciseResultScored $event)
    {
        $schedule = $this->schedulesService->findWith($event->schedule_id, [
            'attendance' => function($q)use($event){
                $q->where('user_id', data_get($event, 'exercise_result.user_id'));
            },
//            'chapter.course',
        ]);

        $attendance = $schedule->attendance;
        if($attendance)
            $this->attendancesService->recountScore($attendance->id);

    }
}
