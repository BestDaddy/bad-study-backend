<?php


namespace App\Http\Controllers\Api\Student;


use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Course\ExerciseResultStoreApiRequest;
use App\Http\Resources\CourseResource;
use App\Http\Resources\ExerciseResource;
use App\Http\Resources\ExerciseResultResource;
use App\Services\Courses\ExerciseResultsService;
use App\Services\Courses\ExercisesService;
use Illuminate\Support\Facades\Auth;

class ExercisesController extends ApiBaseController
{
    private $exerciseResultsService;
    private $exercisesService;

    public function __construct(ExerciseResultsService $exerciseResultsService, ExercisesService $exercisesService)
    {
        $this->exerciseResultsService = $exerciseResultsService;
        $this->exercisesService = $exercisesService;
    }

    public function index(){
        $user = Auth::user();
        $user->load('courses.exercises');

        return $this->successResponse(CourseResource::collection($user->courses));
    }

    public function show($id){
        $user = Auth::user();

        $exercise = $this->exercisesService->findWith($id, [
            'result' => function($q) use($user){
                $q->where('user_id', $user->id);
            },
            'result.attachments',
            'attachments'
        ]);

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
