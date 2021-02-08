<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Group;
use App\Models\GroupCourse;
use App\Models\Schedule;
use App\Services\Groups\GroupsService;
use App\Services\Groups\SchedulesService;
use Illuminate\Http\Request;

class SchedulesController extends Controller
{
    private $schedulesService;

    public function __construct(SchedulesService $schedulesService)
    {
        $this->schedulesService = $schedulesService;
    }

    public function index(Group $group, Course $course){
        $group_course = GroupCourse::where('group_id', $group->id)
            ->where('course_id', $course->id)
            ->first();

        $chapters = $course->chapters;
        if(request()->ajax()){
            return datatables()->of($group_course->schedules()->with(['chapter'])->latest()->get())
                ->addColumn('delete', function($data){
                    return  '<button
                         class=" btn btn-danger btn-sm btn-block "
                         data-id="'.$data->course_id.'"
                         data-course_id="'.$data->course_id.'"
                         onclick="removeCourse(event.target)"><i class="fas fa-trash"  data-id="'.$data->course_id.'" data-course_id="'.$data->course_id.'"></i> Удалить</button>';
                })
                ->addColumn('more', function ($data) use ($group) {
                    return '<a class="text-decoration-none"  href="/groups/'.$group->id.'/courses/'. $data->course_id.'/schedules"><button class="btn btn-primary btn-sm btn-block">Подробнее</button></a>';
                })
                ->rawColumns(['more', 'delete'])
                ->make(true);
        }
        return view('admin.schedules.index', compact('group', 'course', 'chapters'));
    }

    public function store(Group $group, Course $course, Request $request){
        $group_course = GroupCourse::where('group_id', $group->id)
            ->where('course_id', $course->id)
            ->firstOrFail();

        $schedule = Schedule::updateOrCreate(['id' => $request->id],[
            'group_id' => $group->id,
            'chapter_id' => $request->chapter_id,
            'group_course_id' => $group_course->id,
            'starts_at' => $request->starts_at,
            'live_url' => $request->live_url

        ]);
        return response()->json(['code'=>200, 'message'=>'Schedule Saved successfully','data' => $schedule], 200);
    }
}
