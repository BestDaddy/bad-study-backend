<?php


namespace App\Services\Groups;


use Illuminate\Http\Request;

interface AttendancesService
{
    public function store(Request $request);

    public function changeAttendance(Request $request);
}
