<?php


namespace App\Http\Requests\Api\Course;


use App\Http\Requests\ApiBaseRequest;

class ExerciseResultStoreApiRequest extends ApiBaseRequest
{

    public function messages()
    {
        return [
            'user_id.required' => 'Введите ID user',
            'exercise_id.required' => 'Введите ID exercise',
            'value.required' => 'Введите ответ',
        ];
    }

    public function injectedRules()
    {
        return [
            'user_id'       => ['required'],
            'exercise_id'   => ['required'],
            'value'         => ['required'],
        ];
    }
}
