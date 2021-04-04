<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ExerciseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'chapter_id' => $this->chapter_id,
            'name' => $this->name,
            'content' => $this->content,
            'order' => $this->order,
            'result' => $this->when(
                $this->relationLoaded('result'),
                $this->result
            ),
            'attachments' => $this->when(
                $this->relationLoaded('attachments'),
                $this->attachments
            ),
        ];
    }
}
