<?php


namespace App\Services\Groups;


use Illuminate\Http\Request;

interface AttendancesService
{
    public function store(Request $request);
}
