<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ChapterResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'order' => $this->order,
            'schedule' => $this->when(
                $this->relationLoaded('schedule'),
                $this->schedule
            )
        ];
    }
}
