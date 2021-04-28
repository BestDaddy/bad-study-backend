<?php


namespace App\Http\Controllers\Api\Student;


use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Course\ExerciseResultStoreApiRequest;
use App\Http\Resources\CourseResource;

use App\Http\Resources\ScheduleResource;
use App\Models\Course;
use App\Models\GroupCourse;
use App\Services\Courses\CoursesService;
use App\Services\Courses\ExerciseResultsService;
use App\Services\Groups\SchedulesService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoursesController extends ApiBaseController
{
    private $coursesService;
    private $schedulesService;
    private $exerciseResultsService;

    public function __construct(ExerciseResultsService $exerciseResultsService, CoursesService $coursesService, SchedulesService $schedulesService)
    {
        $this->exerciseResultsService = $exerciseResultsService;
        $this->coursesService = $coursesService;
        $this->schedulesService = $schedulesService;
    }

    public function index(){
        $user = Auth::user();
        $user_courses = $user->userCourseGroups()
            ->whereNotNull('group_id')
            ->get();
        $courses = Course::with([
            'userCourseGroup' => function($q) use($user) {
                $q->where('user_id', $user->id);
            },
            'groupCourse' => function($q) use($user_courses) {
                $q->withCount([
                    'schedules as passed_count' => function($qq){
                        $qq->whereDate('starts_at', '<=', Carbon::now()->addHour());
                    },
                    'schedules'
                ])
                    ->whereIn('group_id', $user_courses->pluck('group_id'));
            },
            'groupCourse.teacher',
        ])
            ->whereIn('id', $user_courses->pluck('course_id'))
            ->get();
//        $courses = CourseResource::collection($user->courses()->get());

        return $this->successResponse( CourseResource::collection($courses));
    }

    public function show($id){
//        DB::connection()->enableQueryLog();
        $user = Auth::user();

        $user_course_group = $user->userCourseGroup()
            ->with([])
            ->where('course_id', $id)
            ->firstOrFail();

        $course = $this->coursesService->findWith($id, [
            'groupCourse' => function($q) use($user_course_group){
                $q->where('group_id', $user_course_group->group_id);
            },
            'groupCourse.teacher',
            'groupCourse.schedules.chapter',
            'groupCourse.schedules.attendance' => function($q) use($user_course_group){
                $q->where('user_id', $user_course_group->user_id);
            },
        ]);

        $result = [
            'course' => CourseResource::make($course),
            'schedules' => ScheduleResource::collection($course->groupCourse->schedules),
            'total_passed' => count($course->groupCourse->schedules->filter(function ($item)  {
                return (data_get($item, 'starts_at') < Carbon::now());
            })),
            'total_schedules' => count($course->groupCourse->schedules),
            'total_score' => $user_course_group->score,
        ];
//        dd(DB::getQueryLog());  //6
        return $this->successResponse($result);
    }

    public function scheduleShow($id){
        $user = Auth::user();
        $schedule = $this->schedulesService->findWith($id, [
            'chapter.course',
            'attendance' => function($q) use($user) {
                $q->where('user_id', $user->id);
            },
            'chapter.exercises.attachments' => function($q){
                $q->where('name', 'like', '%.json');
            },
            'chapter.exercises.result' => function ($q) use ($user) {
                $q->where('user_id', $user->id);
            },
            'chapter.exercises.result.attachments',
            'chapter.lectures'
        ]);

        return $this->successResponse(ScheduleResource::make($schedule));
    }
}
