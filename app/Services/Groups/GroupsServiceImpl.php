<?php


namespace App\Services\Groups;

use App\Models\Group;
use App\Models\UserCourseGroup;
use App\Services\BaseServiceImpl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class GroupsServiceImpl extends BaseServiceImpl implements GroupsService
{
    private $attendanceService;

    public function __construct(Group $model, AttendancesService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
        parent::__construct($model);
    }

    public function index(){
        if(request()->ajax())
        {
            return datatables()->of(Group::latest()->get())
                ->addColumn('edit', function($data){
                    return  '<button
                         class=" btn btn-primary btn-sm btn-block "
                          data-id="'.$data->id.'"
                          onclick="editGroup(event.target)"><i class="fas fa-edit" data-id="'.$data->id.'"></i> '.Lang::get('lang.edit').'</button>';
                })
                ->addColumn('more', function ($data){
                    return '<a class="text-decoration-none"  href="/groups/'.$data->id.'"><button class="btn btn-primary btn-sm btn-block">'.Lang::get('lang.more').'</button></a>';
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

    public function addUser(Request $request){
        $group = Group::with('groupCourses')->findOrFail($request->group_id);

        foreach ($group->groupCourses as $groupCourse){
            UserCourseGroup::updateOrCreate(['user_id' => $request->user_id, 'course_id'  => $groupCourse->course_id],
                [
                    'group_id' => $request->group_id,
                ]);
            foreach($groupCourse->schedules as $schedule){
                $request['schedule_id'] = $schedule->id;
                $this->attendanceService->store($request);
            }
        }
    }
}
