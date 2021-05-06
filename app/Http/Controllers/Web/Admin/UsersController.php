<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use App\Models\Group;
use App\Models\Role;
use App\Models\User;
use App\Services\Groups\GroupsService;
use App\Services\Users\UsersService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class UsersController extends Controller
{

    private $usersService;
    private $groupService;

    public function __construct(UsersService $usersService, GroupsService $groupService)
    {
        $this->groupService = $groupService;
        $this->usersService = $usersService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if(request()->ajax()){
            return $this->usersService->index();
        }
        $roles = Role::all();
        return view('admin.users.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $rules = array(
            'first_name'=> 'required',
            'email' => 'required',
            'role_id' => 'required',
            'password' => 'required',
        );
        $error = Validator::make($request->all(), $rules);

        if($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $user = $this->usersService->store($request);
        return response()->json(['code'=>200, 'message'=>'User saved successfully','data' => $user], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        return response()->json($this->usersService->getUser($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function importPage(){
        $groups = Group::all();
        return view('admin.users.import', compact('groups'));
    }

    public function import(Request $request){
        $rules = array(
            'file'=> 'required|mimes:xlsx,xls',
            'group_id' => 'nullable',
        );
        $error = Validator::make($request->all(), $rules);

        if($error->fails()) {
            return back()
                ->withInput()
                ->withErrors(['errors' => $error->errors()->all()]);
        }

        Excel::import(new UsersImport, request()->file('file'));

        if($request->input('group_id')){
            $data = Excel::toArray(new UsersImport(), request()->file('file'));
            $user_emails = [];
            foreach ($data[0] as $key => $value) {
                array_push ($user_emails, $value[0]);
            }

            $users = User::whereIn('email', $user_emails)
                ->select('id')
                ->get();

            foreach ($users as $user){
                $request['user_id'] = $user->id;
                $this->groupService->addUser($request);
            }

        }

        return response()->json(['code'=>200, 'message'=>'User imported successfully',]);
    }
}
