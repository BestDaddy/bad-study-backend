<?php


namespace App\Http\Resources;


use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'group_id' => $this->group_id,
            'chapter_id' => $this->chapter_id,
//            'live_url' => $this->live_url,
            'starts_at' => Carbon::parse($this->starts_at)->format('d M H:i'),
            'attendance' => $this->when(
                $this->relationLoaded('attendance'),
                AttendanceResource::make($this->attendance)
            ),
            'chapter' => $this->when(
                $this->relationLoaded('chapter'),
                ChapterResource::make($this->chapter)
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
