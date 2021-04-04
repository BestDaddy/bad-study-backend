<?php


namespace App\Services\Groups;


use App\Models\UserCourseGroup;
use Illuminate\Http\Request;

interface AttendancesService
{
    public function store(Request $request);

    public function changeAttendance(Request $request);

    public function recountScore($id);

    public function totalRecount(UserCourseGroup $userCourseGroup);
}
