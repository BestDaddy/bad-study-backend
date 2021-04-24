<?php


namespace App\Services\Courses;


use App\Models\Lecture;
use App\Services\BaseServiceImpl;
use Illuminate\Http\Request;

class LecturesServiceImpl extends BaseServiceImpl implements LecturesService
{
    public function __construct(Lecture $model)
    {
        parent::__construct($model);
    }

    public function store(Request $request){
        return Lecture::updateOrCreate(['id' => $request->id], $request->all());
    }
}
