<?php

namespace App\Listeners;

use App\Events\ScheduleCreated;
use App\Services\Groups\AttendancesService;
use App\Services\Groups\GroupsService;
use App\Services\Groups\SchedulesService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;

class SyncScheduleCreated implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    private $schedulesService;
    private $attendancesService;
    private $groupsService;

    public function __construct(GroupsService $groupsService, SchedulesService $schedulesService, AttendancesService $attendancesService)
    {
        $this->groupsService = $groupsService;
        $this->schedulesService = $schedulesService;
        $this->attendancesService = $attendancesService;
    }

    public function handle(ScheduleCreated $event)
    {
        $group = $this->groupsService->findWith($event->schedule->group_id, ['users']);
        $users = $group->users;

        $request = new Request();
        $request['schedule_id'] = $event->schedule->id;
        foreach ($users as $user){
            $request['user_id'] = $user->id;
            $this->attendancesService->store($request);
        }
    }

//    public function subscribe($events)
//    {
//        $events->listen(
//            ScheduleCreated::class,
//            self::class.'@handleScheduleCreated'
//        );
//    }
}
