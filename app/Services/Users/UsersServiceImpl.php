<?php


namespace App\Services\Users;


use App\Models\User;
use Illuminate\Http\Request;

class UsersServiceImpl implements UsersService
{
    public function index(){
        if(request()->ajax())
        {
            return datatables()->of(User::with(['role'])
                ->select([
                    'users.id',
                    'users.first_name',
                    'users.last_name',
                    'users.email',
                    'users.role_id'
                ])
                ->get())
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
        $password = $request->password;
        if($request->id && !$password){
            $password = User::findorfail($request->id)->password;
        }
        elseif($password)
            $password = bcrypt($password);

        $user = User::updateOrCreate(['id' => $request->id],[
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' =>$request->email,
            'role_id' => $request->role_id,
            'password' =>$password,
        ]);

        return $user;
    }

    public function getUser($id){
        return User::findOrFail($id);
    }
}
