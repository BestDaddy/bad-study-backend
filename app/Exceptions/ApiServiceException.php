<?php


namespace App\Exceptions;

use App\Http\Utils\ResponseUtil;
use Exception;

class ApiServiceException extends Exception
{
    protected $code;
    protected $errors;
    protected $success;

    /**
     * ServiceException constructor.
     * @param $code
     * @param $message
     * @param $errors
     */
    public function __construct($code, $success, Array $errors)
    {
        $this->code = $code;
        $this->errors = $errors;
        $this->success = $success;
    }

    public function getApiResponse()
    {
        return ResponseUtil::makeArrayResponse($this->code, $this->success, $this->errors);
    }
}
