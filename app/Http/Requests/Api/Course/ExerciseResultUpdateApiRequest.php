<?php


namespace App\Http\Requests\Api\Course;


use App\Http\Requests\ApiBaseRequest;

class ExerciseResultUpdateApiRequest extends ApiBaseRequest
{
    public function messages()
    {
        return [
            'id.required'=> 'Введите ID',
            'score.required' => 'Введите оценку',
            'schedule_id.required' => 'Введите ID schedule',
        ];
    }

    public function injectedRules()
    {
        return [
            'id'          => ['required'],
            'score'       => ['required', 'numeric', 'max:100', 'min:0'],
            'schedule_id' => ['required'],
        ];
    }
}
