<?php


namespace App\Http\Controllers\Api\Student;


use App\Http\Controllers\ApiBaseController;
use App\Http\Resources\CourseResource;

use App\Http\Resources\ScheduleResource;
use App\Services\Courses\CoursesService;
use App\Services\Groups\SchedulesService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoursesController extends ApiBaseController
{
    private $coursesService;
    private $schedulesService;

    public function __construct(CoursesService $coursesService, SchedulesService $schedulesService)
    {
        $this->coursesService = $coursesService;
        $this->schedulesService = $schedulesService;
    }

    public function index(){
        $user = Auth::user();
        $courses = CourseResource::collection($user->courses()->get());

        return $this->successResponse($courses);
    }

    public function show($id){
//        DB::connection()->enableQueryLog();
        $user = Auth::user();

        $user_course_group = $user->userCourseGroup()
            ->with([])
            ->where('course_id', $id)
            ->firstOrFail();

        $course = $this->coursesService->findWith($id, [
            'chapters.schedule' => function($q) use($user_course_group){
                $q->where('group_id', $user_course_group->group_id);
            },
            'chapters.schedule.attendance' => function($q) use($user_course_group){
                $q->where('user_id', $user_course_group->user_id);
            },
        ]);



        $result = [
            'course' => CourseResource::make($course),
            'total_passed' => count($course->chapters->pluck('schedule')->filter(function ($item)  {
                return (data_get($item, 'starts_at') < Carbon::now());
            })),
            'total_schedules' => count($course->chapters),
            'total_score' => $user_course_group->score,
        ];
//        dd(DB::getQueryLog());  //5
        return $this->successResponse($result);
    }

    public function scheduleShow($id){
        $user = Auth::user();
        $schedule = $this->schedulesService->findWith($id, [
            'chapter.course',
            'attendance' => function($q) use($user) {
                $q->where('user_id', $user->id);
            },
            'chapter.exercises.attachments',
            'chapter.exercises.result' => function ($q) use ($user) {
                $q->where('user_id', $user->id);
            }
        ]);

        return $this->successResponse(ScheduleResource::make($schedule));
    }
}
