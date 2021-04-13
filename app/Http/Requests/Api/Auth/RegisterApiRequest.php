<?php


namespace App\Http\Requests\Api\Auth;


use App\Http\Requests\ApiBaseRequest;

class RegisterApiRequest extends ApiBaseRequest
{
    public function messages()
    {
        return [
            'password.required' => 'Введите пароль',
            'password.confirmed' => 'Пароль не совпадает',
            'email.required' => 'Введите Email',
            'email.email' => 'Неверный формат email',
            'first_name' => 'Введите имя',
            'last_name' => 'Введите фамилию',
            'email.unique' => 'Почта уже зарегистрирована'
        ];
    }

    public function injectedRules()
    {
        return [
            'first_name'=> 'required',
            'last_name'=> 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed'
        ];
    }
}
