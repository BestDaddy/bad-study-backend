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
            'course_id' => $this->course_id,
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
            ),
            'lectures' => $this->when(
                $this->relationLoaded('lectures'),
                LectureResource::collection($this->lectures)
            )
        ];
    }
}
