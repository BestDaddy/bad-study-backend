<?php


namespace App\Services\Courses;


use App\Models\ExerciseResult;
use App\Services\BaseServiceImpl;
use Illuminate\Http\Request;

class ExerciseResultsServiceImpl extends BaseServiceImpl implements ExerciseResultsService
{
    public function __construct(ExerciseResult $model)
    {
        parent::__construct($model);
    }

    public function store(Request $request)
    {
        return ExerciseResult::update(['id' => $request->id],[]);
    }
}
