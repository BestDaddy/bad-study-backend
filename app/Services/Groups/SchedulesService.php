<?php


namespace App\Services\Groups;


use App\Models\Course;
use App\Models\Group;
use App\Models\GroupCourse;
use App\Models\Schedule;
use Illuminate\Http\Request;

interface SchedulesService
{
    public function index(GroupCourse $groupCourse);

    public function store(Group $group, Course $course, Request $request);

    public function attendance(Schedule $schedule);
}
