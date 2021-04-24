<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'courses' => $this->when(
                $this->relationLoaded('courses'),
                CourseResource::collection($this->courses)
            ),
        ];
    }
}
