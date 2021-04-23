<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Group;
use App\Models\GroupCourse;
use App\Models\Schedule;
use App\Services\Groups\AttendancesService;
use App\Services\Groups\GroupsService;
use App\Services\Groups\SchedulesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SchedulesController extends Controller
{
    private $schedulesService;
    private $attendanceService;
    public function __construct(SchedulesService $schedulesService, AttendancesService $attendanceService)
    {
        $this->schedulesService = $schedulesService;
        $this->attendanceService = $attendanceService;
    }

    public function index(Group $group, Course $course){
        $group_course = GroupCourse::where('group_id', $group->id)
            ->where('course_id', $course->id)
            ->firstOrFail();

        $chapters = $course->chapters;
        if(request()->ajax()){
           return $this->schedulesService->index($group_course);
        }
        return view('admin.schedules.index', compact('group', 'course', 'chapters'));
    }

    public function show(Group $group, Course $course, $id){
        $schedule = $this->schedulesService->findWith($id, ['chapter']);
        if(request()->ajax()){
            return $this->schedulesService->attendance($schedule);
        }
        return view('admin.schedules.show', compact('group', 'course', 'schedule'));
    }

    public function store(Group $group, Course $course, Request $request){
        $rules = array(
            'chapter_id'=> 'required',
            'starts_at' =>'required',
        );
        $error = Validator::make($request->all(), $rules);
        if($error->fails())
            return response()->json(['errors' => $error->errors()->all()]);
        $schedule = $this->schedulesService->store($group, $course, $request);
        return response()->json(['code'=>200, 'message'=>'Schedule Saved successfully','data' => $schedule], 200);
    }

    public function edit(Group $group, Course $course, $id){
        return response()->json($this->schedulesService->find($id));

    }

    public function destroy(Group $group, Course $course, $id){
        $this->schedulesService->delete($id);
    }

    public function userResults($schedule_id, $user_id){
//        DB::connection()->enableQueryLog();
        $schedule = $this->schedulesService->findWith(
            $schedule_id, [
                'group.users' => function($q) use($user_id) {
                    $q->where('users.id', $user_id);
                },
                'chapter'
            ]
        );
        $user = $schedule->group->users->first();
        if(!$user){
            abort(404);
        }
        if(request()->ajax()){
            return $this->schedulesService->userResults($schedule, $user);
        }

//        dd(DB::getQueryLog());
        return view('admin.results.index', compact('schedule', 'user'));
    }

}
