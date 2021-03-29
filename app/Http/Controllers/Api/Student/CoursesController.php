<?php


namespace App\Http\Controllers\Api\Student;


use App\Http\Controllers\ApiBaseController;
use App\Http\Resources\CourseResource;

use App\Services\Courses\CoursesService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoursesController extends ApiBaseController
{
    private $coursesService;

    public function __construct(CoursesService $coursesService)
    {
        $this->coursesService = $coursesService;
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
//        dd(DB::getQueryLog());  //5
        return $this->successResponse(CourseResource::make($course));
    }
}
