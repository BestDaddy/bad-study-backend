<?php


namespace App\Http\Controllers;


use App\Http\Utils\ResponseUtil;

class ApiBaseController  extends Controller
{
    public function makeResponse($code, $other)
    {
        return ResponseUtil::makeResponse($code, $other);
    }

    public function successResponse($other)
    {
        return ResponseUtil::makeResponse(200, $other);
    }

    public function failedResponse($other)
    {
        return ResponseUtil::makeResponse(400, $other);
    }

    public function makeWithSuccessFieldResponse($code, $success, $other)
    {
        return ResponseUtil::makeArrayResponse($code, $success, $other);
    }

    public function successWithSuccessFieldResponse($other)
    {
        return ResponseUtil::makeArrayResponse(200, true, $other);
    }

    public function failedWithSuccessFieldResponse($other)
    {
        return ResponseUtil::makeArrayResponse(400, false, $other);
    }
}
