<?php


namespace App\Http\Requests\Api\Auth;


use App\Exceptions\ApiServiceException;
use App\Http\Requests\ApiBaseRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class LoginApiRequest extends ApiBaseRequest
{
    public function messages()
    {
        return [
            'password.required' => 'Введите пароль',
            'email.required' => 'Email обязателен',
            'email.numeric' => 'Неверный формат email',
        ];
    }

    public function injectedRules()
    {
        return [
            'password'  => ['string', 'required'],
            'email'     => ['email', 'required'],
        ];
    }
}
