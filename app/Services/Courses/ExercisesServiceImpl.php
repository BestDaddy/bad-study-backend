<?php


namespace App\Services\Courses;


use App\Models\Exercise;
use Illuminate\Http\Request;

class ExercisesServiceImpl implements ExercisesService
{
    public function store(Request $request){
        return Exercise::updateOrCreate(['id' => $request->id], $request->all());
    }
}

