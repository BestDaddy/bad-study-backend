<?php


namespace App\Http\Controllers\Api\Teacher;


use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Group\ChangeAttendanceApiRequest;
use App\Http\Resources\AttendanceResource;
use App\Http\Resources\ChapterResource;
use App\Http\Resources\ExerciseResource;
use App\Http\Resources\GroupResource;
use App\Http\Resources\Teacher\TeacherScheduleResource;
use App\Models\Attendance;
use App\Models\Exercise;
use App\Models\ExerciseResult;
use App\Models\GroupCourse;
use App\Models\Schedule;
use App\Models\UserCourseGroup;
use App\Services\Groups\AttendancesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SchedulesController extends ApiBaseController
{
    private $attendanceService;

    public function __construct(AttendancesService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function index($group_id, $course_id){
//        DB::connection()->enableQueryLog();
        $user = Auth::user();
        $user_course_groups = UserCourseGroup::where('group_id', $group_id)->where('course_id', $course_id)->get();

        $group_course = GroupCourse::with([
            'schedules.chapter.exercises' => function($q) use($user_course_groups){
                $q->withCount(['results as not_checked' => function($qq) use($user_course_groups){
                    $qq->whereNull('checked_at')->whereIn('user_id', $user_course_groups->pluck('user_id'));
                }]);
            },
            'schedules.attendances' => function($q) use($user_course_groups) {
                $q->whereIn('user_id', $user_course_groups->pluck('user_id'));
            },
            'group'
        ])->where('group_id', $group_id)
            ->where('teacher_id', $user->id)
            ->where('course_id', $course_id)
            ->firstOrFail();

        $result = [
            'group' => GroupResource::make($group_course->group),
            'schedules' => TeacherScheduleResource  ::collection($group_course->schedules),
            'students_count' => $user_course_groups->count(),
        ];
//        dd(DB::getQueryLog());  //7
        return $this->successResponse($result);
    }

    public function attendances($id){
//        DB::connection()->enableQueryLog();
        $user = Auth::user();
        $schedule = Schedule::with([
            'group',
            'chapter',
        ])->findOrFail($id);

        $group_course = $user->teacherGroupCourses()->findOrFail($schedule->group_course_id);
        $user_course_groups = UserCourseGroup::where('group_id', $group_course->group_id)->where('course_id', $group_course->course_id)->get();

        $attendances = Attendance::with(['user'])
            ->where('schedule_id', $schedule->id)
            ->whereIn('user_id', $user_course_groups->pluck('user_id'))
            ->get();

        $result = [
            'group' => GroupResource::make($schedule->group),
            'chapter' => ChapterResource::make($schedule->chapter),
            'attendances' => AttendanceResource::collection($attendances)
        ];
//        dd(DB::getQueryLog());  //6
        return $this->successResponse($result);
    }

    public function changeAttendance(ChangeAttendanceApiRequest $request){
        $attendance = $this->attendanceService->changeAttendance($request);
        return $this->successResponse($attendance);
    }

    public function exercises($id){
        $user = Auth::user();
        $schedule = Schedule::with([
            'group',
            'chapter',
        ])->findOrFail($id);
        $group_course = $user->teacherGroupCourses()->findOrFail($schedule->group_course_id);
        $user_course_groups = UserCourseGroup::where('group_id', $group_course->group_id)->where('course_id', $group_course->course_id)->get();

        $exercises = Exercise::withCount([
            'results' => function($qq) use($user_course_groups) {
                $qq->whereIn('user_id', $user_course_groups->pluck('user_id'));
            },
            'results as not_checked' => function($qq) use($user_course_groups){
                $qq->whereNull('checked_at')->whereIn('user_id', $user_course_groups->pluck('user_id'));
            },
            ])
            ->where('chapter_id', $schedule->chapter_id)
            ->get();
        $result = [
            'group' => GroupResource::make($schedule->group),
            'chapter' => ChapterResource::make($schedule->chapter),
            'exercises' => ExerciseResource::collection($exercises),
            'students_count' => $user_course_groups->count(),
        ];
        return $this->successResponse($result);
    }
}