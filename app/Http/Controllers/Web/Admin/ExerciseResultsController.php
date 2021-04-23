<?php

namespace App\Http\Controllers\Web\Admin;

use App\Events\ExerciseResultScored;
use App\Http\Controllers\Controller;
use App\Models\ExerciseResult;
use App\Services\Courses\ExerciseResultsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExerciseResultsController extends Controller
{
    private $exerciseResultsService;
    public function __construct(ExerciseResultsService $exerciseResultsService)
    {
        $this->exerciseResultsService = $exerciseResultsService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Http\Response
     */
    public function edit($id)
    {
        return  $this->exerciseResultsService->findWith($id, ['exercise', 'user', 'attachments']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $rules = array(
            'score'=> 'required|numeric|min:0|max:100',
        );
        $error = Validator::make($request->all(), $rules);
        if($error->fails())
            return response()->json(['errors' => $error->errors()->all()]);
        $request['status'] = ExerciseResult::STATUS_PASSED;
        $request['checked_at'] = Carbon::now();
        $result =  $this->exerciseResultsService->update($id, $request->all());

        event(new ExerciseResultScored($result, $request->schedule_id));

        return response()->json(['code'=>200, 'message'=>'Result Scored successfully','data' => $result], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
