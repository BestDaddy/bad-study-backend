<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Services\Courses\ExercisesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExercisesController extends Controller
{
    private $exercisesService;

    public function __construct(ExercisesService $exercisesService)
    {
        $this->exercisesService = $exercisesService;
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $rules = array(
            'chapter_id' => 'required',
            'name'=> 'required',
            'content'=> 'required',
            'order' => 'required'
        );
        $error = Validator::make($request->all(), $rules);
        if($error->fails())
            return response()->json(['errors' => $error->errors()->all()]);

        $exercise = $this->exercisesService->store($request);
        return response()->json(['code'=>200, 'message'=>'Exercise Saved successfully','data' => $exercise], 200);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        return response()->json($this->exercisesService->findWith($id, ['attachments']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
