<?php


namespace App\Services\Courses;


use App\Models\Chapter;
use App\Models\Course;
use App\Services\BaseServiceImpl;
use Illuminate\Http\Request;

class ChaptersServiceImpl  extends BaseServiceImpl implements ChaptersService
{
    public function __construct(Chapter $model)
    {
        parent::__construct($model);
    }


    public function store(Request $request){
        return Chapter::updateOrCreate(['id' => $request->id],[
            'name' => $request->name,
            'course_id' => $request->course_id,
            'description' => $request->description,
            'order' => $request->order
        ]);
    }

    public function show($id){
        if(request()->ajax())
        {
            return datatables()->of(Chapter::findOrFail($id)->exercises()->latest()->get())
                ->addColumn('edit', function($data){
                    return  '<button
                         class=" btn btn-primary btn-sm btn-block "
                          data-id="'.$data->id.'"
                          onclick="editExercise(event.target)"><i class="fas fa-edit" data-id="'.$data->id.'"></i> Изменить</button>';
                })
                ->addColumn('more', function ($data){
                    return '<a class="text-decoration-none"  href="/chapters/'.$data->id.'"><button class="btn btn-primary btn-sm btn-block">Подробнее</button></a>';
                })
                ->rawColumns(['more', 'edit'])
                ->make(true);
        }
    }
}
