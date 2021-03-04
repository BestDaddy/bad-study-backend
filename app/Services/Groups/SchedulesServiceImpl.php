<?php


namespace App\Services\Groups;


use App\Models\Course;
use App\Models\Group;
use App\Models\GroupCourse;
use App\Models\Schedule;
use App\Models\User;
use App\Services\BaseServiceImpl;
use Illuminate\Http\Request;

class SchedulesServiceImpl extends BaseServiceImpl implements SchedulesService
{
    public function __construct(Schedule $model)
    {
        parent::__construct($model);
    }

    public function index(GroupCourse $group_course){
        if(request()->ajax()){
            return datatables()->of($group_course->schedules()->with(['chapter'])->latest()->get())
                ->addColumn('edit', function($data){
                    return  '<button
                         class=" btn btn-primary btn-sm btn-block "
                          data-id="'.$data->id.'"
                          onclick="editSchedule(event.target)"><i class="fas fa-edit" data-id="'.$data->id.'"></i> Изменить</button>';
                })
                ->addColumn('more', function ($data) use ($group_course) {
                    return '<a class="text-decoration-none"  href="/groups/'.$group_course->group_id.'/courses/'. $group_course->course_id.'/schedules/'. $data->id.'"><button class="btn btn-primary btn-sm btn-block">Подробнее</button></a>';
                })
                ->rawColumns(['more', 'edit'])
                ->make(true);
        }
    }

    public function store(Group $group, Course $course, Request $request){
        $group_course = GroupCourse::where('group_id', $group->id)
            ->where('course_id', $course->id)
            ->firstOrFail();

        return Schedule::updateOrCreate(['id' => $request->id],[
            'group_id' => $group->id,
            'chapter_id' => $request->chapter_id,
            'group_course_id' => $group_course->id,
            'starts_at' => $request->starts_at,
            'live_url' => $request->live_url

        ]);
    }

    public function attendance(Schedule $schedule){
        $group = $schedule->group()->with(['users'])->firstOrFail();

        if(request()->ajax()){
            return datatables()->of($schedule->attendance()
                ->whereIn('user_id', $group->users->flatten()->pluck('id'))
                ->with(['user'])
                ->latest()
                ->get())
                ->addColumn('change', function($data){
                    if($data->value){
                        return '
                        <form>
                          <input type="checkbox"
                          data-id="'.$data->id.'"
                          data-value="0"
                          value="0"
                          onclick="changeAttendance(event.target)"
                          checked>
                        </form>';
                    } else {
                        return '
                        <form>
                          <input type="checkbox"
                          data-id="'.$data->id.'"
                          data-value="1"
                          value="1"
                          onclick="changeAttendance(event.target)">
                        </form>';
                    }
                })
                ->addColumn('full_name', function($data){
                    return $data->user->first_name . ' ' .  $data->user->last_name;
                })
                ->addColumn('results', function ($data){
                    return '<a class="text-decoration-none"  href="/schedules/'. $data->schedule_id .'/users/'. $data->user_id.'"><button class="btn btn-primary btn-sm btn-block">Ответы</button></a>';
                })
                ->rawColumns(['change', 'full_name', 'results'])
                ->make(true);
        }
    }

    public function userResults(Schedule $schedule, User $user){
        $exercises = $schedule->chapter->exercises;

        if(request()->ajax()){
            return datatables()->of($user->exerciseResults()->with(['exercise'])
                ->whereIn('exercise_id', $exercises->pluck('id'))
                ->latest()
                ->get())
                ->addColumn('edit', function($data){
                    return  '<button
                         class=" btn btn-primary btn-sm btn-block "
                          data-id="'.$data->id.'"
                          onclick="editAnswer(event.target)"><i class="fas fa-edit" data-id="'.$data->id.'"></i> Оценить</button>';
                })
                ->rawColumns(['edit'])
                ->make(true);
        }
    }
}
