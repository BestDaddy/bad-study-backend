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
            'score' => $this->score,
            'value' => $this->value,
            'status' => Attendance::getStatusTexts()[$this->status],
        ];

    }
}
