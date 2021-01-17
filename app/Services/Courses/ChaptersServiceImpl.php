<?php


namespace App\Services\Courses;


use App\Models\Chapter;
use Illuminate\Http\Request;

class ChaptersServiceImpl implements ChaptersService
{
    public function store(Request $request){
        return Chapter::updateOrCreate(['id' => $request->id],[
            'name' => $request->name,
            'course_id' => $request->course_id,
            'description' => $request->description,
            'order' => $request->order
        ]);
    }
}
