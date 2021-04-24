<?php


namespace App\Http\Controllers\Web\Admin;


use App\Http\Controllers\Controller;
use App\Services\Courses\LecturesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LecturesController extends Controller
{
    private $lecturesService;

    public function __construct(LecturesService $lecturesService)
    {
        $this->lecturesService = $lecturesService;
    }

    public function store(Request $request){
        $rules = array(
            'chapter_id' => 'required',
            'title'=> 'required',
            'content'=> 'required',
            'order' => 'required'
        );
        $error = Validator::make($request->all(), $rules);
        if($error->fails())
            return response()->json(['errors' => $error->errors()->all()]);

        $lecture = $this->lecturesService->store($request);

        return response()->json(['code'=>200, 'message'=>'Lecture Saved successfully','data' => $lecture], 200);
    }

    public function show($id){
        $lecture = $this->lecturesService->find($id);
        return view('admin.lectures.show', compact('lecture'));
    }

    public function edit($id){
        return response()->json($this->lecturesService->find($id));
    }

    public function destroy($id){
        $this->lecturesService->delete($id);
        return response()->json('Lecture deleted successfully');
    }
}
