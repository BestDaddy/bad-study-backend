<?php


namespace App\Services\Courses;


use Illuminate\Http\Request;

interface ExerciseResultsService
{
    public function store(Request $request);
}
