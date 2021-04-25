<?php


namespace App\Http\Controllers\Api\Teacher;


use App\Http\Controllers\ApiBaseController;
use App\Http\Resources\GroupResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroupsController extends ApiBaseController
{
    public function index(Request $request){
//        DB::connection()->enableQueryLog();
        $user = Auth::user();
        $user->load([
            'teacherGroups.courses' => function($q) use ($user){
                $q->wherePivot('teacher_id' , $user->id);
            },
            'teacherGroups' => function($q){
                $q->withCount(['users as student_count' => function ($qq){
                    $qq->select(DB::raw('count(distinct(user_id))'));
                }]);
            },
        ]);
//        dd(DB::getQueryLog());  //2
        return $this->successResponse(GroupResource::collection($user->teacherGroups));
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
