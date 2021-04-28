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
            'user_course' => $this->when(
                $this->relationLoaded('userCourseGroup'),
                UserCourseGroupResource::make($this->userCourseGroup)
            ),
            'passed_count' => $this->when(
                $this->relationLoaded('groupCourse'),
                data_get($this,'groupCourse.passed_count')
            ),
            'schedules_count' => $this->when(
                $this->relationLoaded('groupCourse'),
                data_get($this,'groupCourse.schedules_count')
            ),
            'teacher' => $this->when(
                $this->relationLoaded('groupCourse'),
                $this->when(
                    $this->groupCourse->relationLoaded('teacher'),
                    UserResource::make($this->groupCourse->teacher)
                )
            ),
        ];
    }
}
