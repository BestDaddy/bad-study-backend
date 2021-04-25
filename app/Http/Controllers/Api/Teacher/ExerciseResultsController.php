<?php


namespace App\Http\Controllers\Api\Teacher;


use App\Events\ExerciseResultScored;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Course\ExerciseResultUpdateApiRequest;
use App\Models\ExerciseResult;
use App\Services\Courses\ExerciseResultsService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExerciseResultsController extends ApiBaseController
{
    private $exerciseResultsService;
    public function __construct(ExerciseResultsService $exerciseResultsService)
    {
        $this->exerciseResultsService = $exerciseResultsService;
    }

    public function edit($id){
        return $this->successResponse($this->exerciseResultsService->findWith($id, ['user', 'attachments']));
    }

    public function update(ExerciseResultUpdateApiRequest $request){
        $request['status'] = ExerciseResult::STATUS_PASSED;
        $request['checked_at'] = Carbon::now();
        $result =  $this->exerciseResultsService->update($request->id, $request->all());

        event(new ExerciseResultScored($result, $request->schedule_id));
        return $this->successResponse($result);
    }
}
