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
            'student_count' => $this->when(
                $this->student_count,
                $this->student_count
            ),
            'courses' => $this->when(
                $this->relationLoaded('courses'),
                CourseResource::collection($this->courses)
            ),
        ];
    }
}
