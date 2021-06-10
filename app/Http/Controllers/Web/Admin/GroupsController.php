<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupCourse;
use App\Models\Role;
use App\Models\User;
use App\Models\UserCourseGroup;
use App\Services\Groups\AttendancesService;
use App\Services\Groups\GroupsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class GroupsController extends Controller
{
    private $groupsService;
    private $attendanceService;
    public function __construct(GroupsService $groupsService, AttendancesService $attendanceService)
    {
        $this->groupsService = $groupsService;
        $this->attendanceService = $attendanceService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if(request()->ajax()){
            return $this->groupsService->index();
        }
        return view('admin.groups.index');
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
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $rules = array(
            'name'=> 'required',
        );
        $error = Validator::make($request->all(), $rules);
        if($error->fails())
            return response()->json(['errors' => $error->errors()->all()]);

        $group = $this->groupsService->store($request);
        return response()->json(['code'=>200, 'message'=>'Group Saved successfully','data' => $group], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function show($id)
    {
        $teachers = User::where('role_id', Role::TEACHER_ID)->get();
        $group = Group::findOrFail($id);
        if(request()->ajax()){
            return datatables()->of($group->groupCourses()->with(['course', 'teacher'])->latest()->get())
                ->addColumn('delete', function($data){
                    return  '<button
                         class=" btn btn-danger btn-sm btn-block "
                         data-id="'.$data->course_id.'"
                         data-course_id="'.$data->course_id.'"
                         onclick="removeCourse(event.target)"><i class="fas fa-trash"  data-id="'.$data->course_id.'" data-course_id="'.$data->course_id.'"></i> '.Lang::get('lang.delete').'</button>';
                })
                ->addColumn('more', function ($data) use ($group) {
                    return '<a class="text-decoration-none"  href="/groups/'.$group->id.'/courses/'. $data->course_id.'/schedules"><button class="btn btn-primary btn-sm btn-block">'.Lang::get('lang.more').'</button></a>';
                })
                ->rawColumns(['more', 'delete'])
                ->make(true);
        }
        return view('admin.groups.show', compact('group', 'teachers'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function edit($id)
    {
        return response()->json($this->groupsService->find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
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
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $this->groupsService->delete($id);
        return response()->json('Group deleted successfully');
    }

    public function getNewCourses($id){
        return datatables()->of( DB::table("courses")
            ->select('*')->whereNotIn('courses.id',function($query) use ($id) {
                $query->select('course_id')->from('group_course')->where('group_course.group_id', $id);
            })->latest()->get())
            ->addColumn('add', function ($data){
                return '<button  id="'.$data->id.'" class="btn btn-primary btn-sm btn-block" data-id="'.$data->id.'"  onclick="addCourse(event.target)"><i class="fas fa-plus" data-id="'.$data->id.'"></i> '.Lang::get('lang.edit').'</button>';
            })
            ->rawColumns(['add'])
            ->make(true);
    }

    public function getNewStudents($id){
        $group_courses = GroupCourse::where('group_id', $id)->get();
        return datatables()->of( User::where('role_id', Role::STUDENT_ID)->whereNotIn('id',
                UserCourseGroup::whereNotNull('group_id')->whereIn('course_id', $group_courses->pluck('course_id'))
                    ->get()->pluck('user_id')
            )->latest()->get())
            ->addColumn('add', function ($data){
                return '<button  id="'.$data->id.'" class="btn btn-primary btn-sm btn-block" data-id="'.$data->id.'"  onclick="addUser(event.target)"><i class="fas fa-plus" data-id="'.$data->id.'"></i> '.Lang::get('lang.add').'</button>';
            })
            ->rawColumns(['add'])
            ->make(true);
    }

    public function getStudents($id){
        return datatables()->of( Group::findOrFail($id)->users()->latest()->get())
            ->addColumn('delete', function ($data){
                return '<button  id="'.$data->id.'" class="btn btn-danger btn-sm btn-block" data-id="'.$data->id.'"  onclick="removeUser(event.target)"><i class="fas fa-trash" data-id="'.$data->id.'"></i> '.Lang::get('lang.delete').'</button>';
            })
            ->rawColumns(['delete'])
            ->make(true);
    }

    public function addCourse(Request $request){
        $rules = array(
            'course_id' => 'required',
            'group_id'  => 'required',
            'teacher_id' => 'required',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
            return response()->json(['errors' => $error->errors()->all()]);
        //add course
        $group_course = GroupCourse::updateOrCreate(['course_id' => $request->course_id, 'group_id'  => $request->group_id,],
            [
                'teacher_id' => $request->teacher_id,
            ]);
        //add course for each student
        $group = Group::with('users')->findOrFail($request->group_id);
        foreach ($group->users as $user){
            UserCourseGroup::updateOrCreate(['user_id' => $user->id, 'course_id'  => $request->course_id],
                [
                    'group_id' => $request->group_id,
                ]);
        }

        return response()->json(['code'=>200, 'message'=>'Group Saved successfully','data' => $group_course], 200);
    }

    public function removeCourse(Request $request){
        $rules = array(
            'course_id' => 'required',
            'group_id'  => 'required',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
            return response()->json(['errors' => $error->errors()->all()]);
        // remove course
        GroupCourse::where('group_id', $request->group_id)
            ->where('course_id', $request->course_id)
            ->delete();
        // remove student with the course
        UserCourseGroup::where('group_id', $request->group_id)
            ->where('course_id', $request->course_id)
            ->update(['group_id' => null]);
        return response()->json(['code'=>200, 'message'=>'Course removed successfully'], 200);
    }

    public function addUser(Request $request){
        $rules = array(
            'user_id' => 'required',
            'group_id'  => 'required',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
            return response()->json(['errors' => $error->errors()->all()]);

        $this->groupsService->addUser($request);

        return response()->json(['code'=>200, 'message'=>'User Saved successfully'], 200);
    }

    public function removeUser(Request $request){
        $rules = array(
            'user_id' => 'required',
            'group_id'  => 'required',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
            return response()->json(['errors' => $error->errors()->all()]);

        $group_course = GroupCourse::where('group_id', $request->group_id)->get();
        UserCourseGroup::whereIn('course_id', $group_course->pluck('course_id'))
            ->where('user_id', $request->user_id)
            ->update(['group_id' => null]);

        return response()->json(['code'=>200, 'message'=>'User removed successfully'], 200);
    }
}
