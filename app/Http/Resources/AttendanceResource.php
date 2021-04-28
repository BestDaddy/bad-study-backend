<?php


namespace App\Http\Resources;


use App\Models\Attendance;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'schedule_id' => $this->schedule_id,
            'score' => number_format($this->score, 1),
            'value' => $this->value,
            'status' => Attendance::getStatusTexts()[$this->status],
            'user' => $this->when(
                $this->relationLoaded('user'),
                UserResource::make($this->user)
            ),
        ];

    }
}
