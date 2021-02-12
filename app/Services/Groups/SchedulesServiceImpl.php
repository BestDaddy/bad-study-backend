<?php


namespace App\Services\Groups;


use App\Models\Course;
use App\Models\Group;
use App\Models\GroupCourse;
use App\Models\Schedule;
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
        if(request()->ajax()){
            return datatables()->of($schedule->attendance()->with(['user'])->latest()->get())
//                ->addColumn('edit', function($data){
//                    return  '<button
//                         class=" btn btn-primary btn-sm btn-block "
//                          data-id="'.$data->id.'"
//                          onclick="editSchedule(event.target)"><i class="fas fa-edit" data-id="'.$data->id.'"></i> Изменить</button>';
//                })
//                ->addColumn('more', function ($data) use ($group_course) {
//                    return '<a class="text-decoration-none"  href="/groups/'.$group_course->group_id.'/courses/'. $data->course_id.'/schedules"><button class="btn btn-primary btn-sm btn-block">Подробнее</button></a>';
//                })
//                ->rawColumns(['more', 'edit'])
                ->make(true);
        }

    }
}
