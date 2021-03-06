<?php


namespace App\Services\Courses;


use Illuminate\Http\Request;

interface ChaptersService
{
    public function store(Request $request);

    public function show($id);

    public function lectures($id);
}
