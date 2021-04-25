<?php


namespace App\Http\Requests\Api\Group;


use App\Http\Requests\ApiBaseRequest;

class ChangeAttendanceApiRequest extends ApiBaseRequest
{
    public function messages()
    {
        return [
            'id.required' => 'Введите ID attendance',
            'value.required' => 'Введите 0 || 1',
        ];
    }

    public function injectedRules()
    {
        return [
            'id' => 'required',
            'value'=> 'required'
        ];
    }
}
