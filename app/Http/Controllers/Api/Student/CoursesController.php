<?php


namespace App\Http\Controllers\Api\Student;


use App\Http\Controllers\ApiBaseController;
use App\Services\Courses\CoursesService;
use Illuminate\Support\Facades\Auth;

class CoursesController extends ApiBaseController
{
    private $coursesService;

    public function __construct(CoursesService $coursesService)
    {
        $this->coursesService = $coursesService;
    }

    public function index(){
        $user = Auth::user();
        $courses = $user->courses()->get();

        return $this->successResponse($courses);
    }
}
