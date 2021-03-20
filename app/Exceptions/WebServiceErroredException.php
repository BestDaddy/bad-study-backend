<?php


namespace App\Exceptions;


class WebServiceErroredException extends Exception
{
    protected $explanation;

    /**
     * WebServiceErroredException constructor.
     * @param $explanation
     */
    public function __construct($explanation)
    {
        $this->explanation = $explanation;
    }


    /**
     * @return mixed
     */
    public function getExplanation()
    {
        return $this->explanation;
    }
}
