<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'group_id' => $this->group_id,
            'chapter_id' => $this->chapter_id,
            'live_url' => $this->live_url,
            'starts_at' => $this->starts_at,
            'attendance' => $this->when(
                $this->relationLoaded('attendance'),
                AttendanceResource::make($this->attendance)
            ),
            'chapter' => $this->when(
                $this->relationLoaded('chapter'),
                ChapterResource::make($this->chapter)
            ),
        ];
    }
}
