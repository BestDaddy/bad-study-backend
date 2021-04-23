<?php


namespace App\Http\Requests\Api\Support;


use App\Http\Requests\ApiBaseRequest;

class AttachmentStoreApiRequest extends ApiBaseRequest
{
    public function messages()
    {
        return [
            'model_id.required' => 'Введите ID result',
            'model_type.required' => 'Введите result',
            'file' => 'Файл не найден'
        ];
    }

    public function injectedRules()
    {
        return [
            'model_id'       => ['required'],
            'model_type'   => ['required'],
            'file' => ['required'],
        ];
    }
}
