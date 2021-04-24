<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class LectureResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
            'order' => $this->order,
        ];
    }
}
