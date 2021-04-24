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
            }
        ]);
//        dd(DB::getQueryLog());  //6
        return $this->successResponse(  ($user->teacherGroups));
    }
}
