<?php


namespace App\Exceptions;


use Exception;

class WebServiceException extends Exception
{
    protected $validator;
    protected $inputs;
    protected $prevRoute;

    /**
     * WebServiceException constructor.
     * @param $validator
     * @param $inputs
     * @param $prevRoute
     */
    public function __construct($validator, $inputs, $prevRoute = null)
    {
        $this->validator = $validator;
        $this->inputs = $inputs;
        $this->prevRoute = $prevRoute;
    }

    /**
     * @return mixed
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     * @return mixed
     */
    public function getInputs()
    {
        return $this->inputs;
    }

    /**
     * @return null
     */
    public function getPrevRoute()
    {
        return $this->prevRoute;
    }
}
