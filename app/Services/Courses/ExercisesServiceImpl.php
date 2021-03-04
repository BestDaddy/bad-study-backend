<?php


namespace App\Services\Courses;


use App\Models\Exercise;
use App\Services\BaseServiceImpl;
use Illuminate\Http\Request;

class ExercisesServiceImpl extends BaseServiceImpl implements ExercisesService
{
    public function __construct(Exercise $model)
    {
        parent::__construct($model);
    }

    public function store(Request $request){
        return Exercise::updateOrCreate(['id' => $request->id], $request->all());
    }
}

