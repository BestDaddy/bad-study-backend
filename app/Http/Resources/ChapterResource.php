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
                ScheduleResource::make($this->schedule)
            ),
            'course' =>$this->when(
                $this->relationLoaded('course'),
                CourseResource::make($this->course)
            ),
            'exercises' => $this->when(
                $this->relationLoaded('exercises'),
                ExerciseResource::collection($this->exercises)
            )
        ];
    }
}
