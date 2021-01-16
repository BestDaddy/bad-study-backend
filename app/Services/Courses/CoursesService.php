<?php


namespace App\Services\Courses;


use Illuminate\Http\Request;

interface CoursesService
{
    public function index();

    public function store(Request $request);
}
