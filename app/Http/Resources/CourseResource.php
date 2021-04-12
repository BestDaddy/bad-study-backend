<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'pivot' => $this->when(
                $this->pivot,
                $this->pivot
            ),
            'chapters' => $this->when(
                $this->relationLoaded('chapters'),
                ChapterResource::collection($this->chapters)
            ),
            'exercises' => $this->when($this->relationLoaded('exercises'),
                ExerciseResource::collection($this->exercises)
            ),
        ];
    }
}
