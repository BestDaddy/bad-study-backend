<?php


namespace App\Http\Requests;


use App\Exceptions\ApiServiceException;
use App\Http\Errors\ErrorCode;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

abstract class ApiBaseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public abstract function injectedRules();

    public function rules()
    {
        return $this->injectedRules();
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ApiServiceException(400, false, [
            'errorCode' => ErrorCode::INVALID_FIELD,
            'errors' => $validator->errors()
        ]);
    }
}
