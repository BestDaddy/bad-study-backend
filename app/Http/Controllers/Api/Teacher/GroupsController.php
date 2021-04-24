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
        $user->load('teacherGroupCourses');
//        dd(DB::getQueryLog());  //6
        return $this->successResponse(  ($user->teacherGroupCourses));
    }
}
