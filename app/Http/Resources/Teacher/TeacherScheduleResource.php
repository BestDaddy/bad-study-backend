<?php


namespace App\Http\Resources\Teacher;


use App\Http\Resources\AttendanceResource;
use App\Http\Resources\ChapterResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherScheduleResource extends JsonResource
{
    public function toArray($request)
    {
        $not_checked =  $this->when(
            $this->relationLoaded('chapter'),
            $this->chapter->exercises->sum('not_checked')
        );
        $attendances = $this->when(
            $this->relationLoaded('attendances'),
            $this->attendances
        );

        return [
            'id' => $this->id,
            'starts_at' => $this->starts_at,
            'title' => $this->chapter->name,
            'not_checked' => $not_checked ?? 0,
            'attended' => $this->when(
                $this->relationLoaded('attendances'),
                $attendances->where('value', true)->count()
            ),
            'average_score' => $this->when(
                $this->relationLoaded('attendances'),
                number_format($attendances->average('score'), 1)
            ),
        ];
    }
}
