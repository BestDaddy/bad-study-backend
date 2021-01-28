<?php


namespace App\Services\Groups;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupsServiceImpl implements GroupsService
{
    public function index(){
        if(request()->ajax())
        {
            return datatables()->of(Group::latest()->get())
                ->addColumn('edit', function($data){
                    return  '<button
                         class=" btn btn-primary btn-sm btn-block "
                          data-id="'.$data->id.'"
                          onclick="editCourse(event.target)"><i class="fas fa-edit" data-id="'.$data->id.'"></i> Изменить</button>';
                })
                ->addColumn('more', function ($data){
                    return '<a class="text-decoration-none"  href="/groups/'.$data->id.'"><button class="btn btn-primary btn-sm btn-block">Подробнее</button></a>';
                })
                ->rawColumns(['more', 'edit'])
                ->make(true);
        }
    }

    public function store(Request $request){
        return Group::updateOrCreate(['id' => $request->id],[
            'name' => $request->name,
            'description' => $request->description,
            'chat' => $request->chat,
        ]);
    }
}
