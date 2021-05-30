<?php


namespace App\Services\Courses;


use App\Models\Chapter;
use App\Models\Course;
use App\Services\BaseServiceImpl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

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
                          onclick="editExercise(event.target)"><i class="fas fa-edit" data-id="'.$data->id.'"></i> '.Lang::get('lang.edit').'</button>';
                })
                ->addColumn('more', function ($data){
                    return '<a class="text-decoration-none"  href="/exercises/'.$data->id.'"><button class="btn btn-primary btn-sm btn-block">'.Lang::get('lang.more').'</button></a>';
                })
                ->rawColumns(['more', 'edit'])
                ->make(true);
        }
    }

    public function lectures($id){
        if(request()->ajax())
        {
            return datatables()->of(Chapter::findOrFail($id)->lectures()->latest()->get())
                ->addColumn('edit', function($data){
                    return  '<button
                         class=" btn btn-primary btn-sm btn-block "
                          data-id="'.$data->id.'"
                          onclick="editLecture(event.target)"><i class="fas fa-edit" data-id="'.$data->id.'"></i> '.Lang::get('lang.edit').'</button>';
                })
                ->addColumn('more', function ($data){
                    return '<a class="text-decoration-none"  href="/lectures/'.$data->id.'"><button class="btn btn-primary btn-sm btn-block">'.Lang::get('lang.more').'</button></a>';
                })
                ->rawColumns(['more', 'edit'])
                ->make(true);
        }
    }
}
