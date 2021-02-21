<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Services\Groups\AttendancesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendancesController extends Controller
{
    private $attendanceService;

    public function __construct(AttendancesService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function changeAttendance(Request $request){
        $rules = array(
            'id' => 'required',
            'value'=> 'required'
        );
        $error = Validator::make($request->all(), $rules);
        if($error->fails())
            return response()->json(['errors' => $error->errors()->all()]);

        $attendance = $this->attendanceService->changeAttendance($request);
        return response()->json(['code'=>200, 'message'=>'Attendance Changed successfully','data' => $attendance], 200);
    }
}
