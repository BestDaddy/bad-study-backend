<?php


namespace App\Services\Groups;


use App\Models\Attendance;
use App\Services\BaseServiceImpl;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendancesServiceImpl extends BaseServiceImpl implements AttendancesService
{
    public function __construct(Attendance $model)
    {
        parent::__construct($model);
    }

    public function store(Request $request){
        return Attendance::updateOrCreate([
            'user_id' => $request->user_id,
            'schedule_id' => $request->schedule_id
        ],[
            $request->all()
        ]);
    }
}
