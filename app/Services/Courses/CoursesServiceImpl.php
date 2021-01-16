<?php


namespace App\Services\Courses;


use App\Models\Course;
use Illuminate\Http\Request;

class CoursesServiceImpl implements CoursesService
{
    public function index(){
        if(request()->ajax())
        {
            return datatables()->of(Course::latest()->get())
                ->addColumn('edit', function($data){
                    return  '<button
                         class=" btn btn-primary btn-sm btn-block "
                          data-id="'.$data->id.'"
                          onclick="editUser(event.target)"><i class="fas fa-edit" data-id="'.$data->id.'"></i> Изменить</button>';
                })
                ->addColumn('more', function ($data){
                    return '<a class="text-decoration-none"  href="users/'.$data->id.'"><button class="btn btn-primary btn-sm btn-block">Подробнее</button></a>';
                })
                ->rawColumns(['more', 'edit'])
                ->make(true);
        }
    }

    public function store(Request $request){
        return Course::updateOrCreate(['id' => $request->id],[
            'name' => $request->name,
            'description' => $request->description,
        ]);
    }
}
