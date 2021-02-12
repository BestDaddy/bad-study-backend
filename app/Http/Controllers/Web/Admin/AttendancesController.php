<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Services\Groups\AttendancesService;
use Illuminate\Http\Request;

class AttendancesController extends Controller
{
    private $attendanceService;

    public function __construct(AttendancesService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }


}
