<?php


namespace App\Http\Controllers\Api\Student;


use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Course\ExerciseResultStoreApiRequest;
use App\Http\Resources\CourseResource;
use App\Http\Resources\ExerciseResource;
use App\Http\Resources\ExerciseResultResource;
use App\Http\Resources\ScheduleResource;
use App\Models\Schedule;
use App\Services\Courses\ExerciseResultsService;
use App\Services\Courses\ExercisesService;
use App\Services\Groups\SchedulesService;
use Illuminate\Support\Facades\Auth;

class ExercisesController extends ApiBaseController
{
    private $exerciseResultsService;
    private $exercisesService;
    private $schedulesService;
    public function __construct(SchedulesService $schedulesService, ExerciseResultsService $exerciseResultsService, ExercisesService $exercisesService)
    {
        $this->schedulesService = $schedulesService;
        $this->exerciseResultsService = $exerciseResultsService;
        $this->exercisesService = $exercisesService;
    }

    public function index(){
        $user = Auth::user();
        $user->load([
            'courses.exercises' => function($q){
                $q->select('exercises.id', 'exercises.name', 'exercises.order');
            }
        ]);

        return $this->successResponse(CourseResource::collection($user->courses));
    }

    public function show($id){
        $user = Auth::user();
        $exercise = $this->exercisesService->findWith($id, [
            'result' => function($q) use($user){
                $q->where('user_id', $user->id);
            },
            'result.attachments',
            'attachment' => function($q){
                $q->where('name', 'like', '%.json');
            },
            'chapter' => function($q){
                $q->select('id', 'name', 'course_id');
            }
        ]);

        $user->userCourseGroup()
            ->where('course_id', data_get($exercise, 'chapter.course_id'))
            ->firstOrFail();
//        $schedule = Schedule::where('chapter_id', $exercise->chapter_id)
//            ->whereHas('group.users', function ($q) use ($user) {
//                $q->where('users.id', $user->id);
//            })->firstOrFail();
//        $result = [
//            'exercise' => ,
////            'schedule' => ScheduleResource::make($schedule),
//        ];

        return $this->successResponse(ExerciseResource::make($exercise));
    }

    public function indexExerciseResult(){
        $user = Auth::user();
        $user->load('exerciseResults.exercise');

        return $this->successResponse(ExerciseResultResource::collection($user->exerciseResults));
    }

    public function exerciseResultStore(ExerciseResultStoreApiRequest $request){
        $execise_result = $this->exerciseResultsService->store($request);
        return $this->successResponse($execise_result);
    }
}
