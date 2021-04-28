<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class UserCourseGroupResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'course_id' => $this->course_id,
            'group_id' => $this->group_id,
            'user_id' => $this->user_id,
            'score' => number_format($this->score, 1),
            'status' => $this->status,
        ];
    }
}
