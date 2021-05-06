<?php


namespace App\Http\Controllers\Api\Teacher;


use App\Http\Controllers\ApiBaseController;
use App\Http\Resources\GroupCollection;
use App\Http\Resources\GroupResource;
use App\Http\Resources\ScheduleResource;
use App\Http\Resources\Teacher\TeacherScheduleResource;
use App\Models\GroupCourse;
use App\Models\UserCourseGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroupsController extends ApiBaseController
{
    public function index(Request $request){
//        DB::connection()->enableQueryLog();
        $order = $request->input('order', 'desc');
        $column = $request->input('column', 'id');
        $search = $request->input('search', null);
        $paginate = $request->input('paginate', 5);

        $user = Auth::user();
        $groups = $user->teacherGroups()->with([
            'courses',
            ])
            ->withCount([
                'users as student_count' => function ($qq){
                    $qq->select(DB::raw('count(distinct(user_id))'));
                }
            ])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($qq) use ($search) {
                    $qq->whereRaw('LOWER(`name`) LIKE ?',['%'.trim(strtolower($search)).'%']);
                });
            })
            ->orderBy($column, $order)
            ->paginate($paginate);
//        dd(DB::getQueryLog());  //2
        return new GroupCollection($groups);
    }

    public function show($id){
        $user = Auth::user();
        $group = $user->teacherGroups()->with([
            'courses' => function($q) use ($user){
                $q->wherePivot('teacher_id' , $user->id);
            },
            ])
            ->withCount(['users as student_count' => function ($qq){
                $qq->select(DB::raw('count(distinct(user_id))'));
            }])
            ->findOrFail($id);
        return $this->successResponse(GroupResource::make($group));
    }

    public function students($group_id, $course_id, Request $request){
        $column = $request->input('column', 'id');
        $order = $request->input('order', 'desc');
        $search = $request->input('search', null);
        $user = Auth::user();
        $group = $user->teacherGroups()->with([
            'users' => function($q) use($search, $order, $column, $course_id){
                $q->wherePivot('course_id' ,$course_id)
                    ->when($search, function ($qq) use ($search) {
                        $qq->where(function ($qqq) use ($search){
                            $qqq->where('first_name', 'like', '%' . $search . '%')
                                ->orWhere('last_name', 'like', '%' . $search . '%')
                                ->orWhere('email', 'like', '%' . $search . '%');
                        });
                    })
                    ->orderBy($column, $order);
            },
        ])->findOrFail($group_id);

        $students = $group->users;

        return $this->successResponse(($students));
    }
}
