<?php


namespace App\Services\Groups;


use App\Models\Attendance;
use App\Models\ExerciseResult;
use App\Models\UserCourseGroup;
use App\Services\BaseServiceImpl;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendancesServiceImpl extends BaseServiceImpl implements AttendancesService
{
    public function __construct(Attendance $model)
    {
        parent::__construct($model);
    }

    public function store(Request $request){
        return Attendance::updateOrCreate([
            'user_id' => $request->user_id,
            'schedule_id' => $request->schedule_id
        ],[
            $request->all()
        ]);
    }

    public function changeAttendance(Request $request){
        $attendance = Attendance::findOrFail($request->id);
        $attendance->value = $request->value;
        $attendance->save();
        return $attendance;
    }

    public function recountScore($id){
        $attendance = $this->findWith($id, ['schedule.chapter.exercises']);

        $exercise_results = ExerciseResult::whereIn(
            'exercise_id',
            $attendance->schedule->chapter->exercises->pluck('id')
            )
            ->where('user_id', $attendance->user_id)
            ->get();

        $score = $exercise_results->sum('score') / count($attendance->schedule->chapter->exercises);

        $this->update($id, [
            'score' => intval($score),
            'status' => Attendance::STATUS_PASSED
        ]);
    }

    public function totalRecount(UserCourseGroup $userCourseGroup){
        $userCourseGroup->load([
            'course.groupCourse' => function($q) use($userCourseGroup){
                $q->where('group_id', $userCourseGroup->group_id);
            },
            'course.groupCourse.schedules.attendance' => function($q) use($userCourseGroup){
                $q->where('user_id', $userCourseGroup->user_id);
            },
            'course.chapters'
            ]);

        $schedules = data_get($userCourseGroup, 'course.groupCourse.schedules');
        $scores = $schedules->pluck('attendance')->sum('score');

        $score = $scores  / count($schedules);

        $userCourseGroup->update([
            'score' => intval($score)
        ]);

    }
}
