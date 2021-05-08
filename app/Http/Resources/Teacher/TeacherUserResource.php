<?php


namespace App\Http\Resources\Teacher;


use Illuminate\Http\Resources\Json\JsonResource;

class TeacherUserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'role' => $this->when(
                $this->relationLoaded('role'),
                $this->role
            ),
            'attendances_count' => $this->when(
                $this->attendances_count,
                $this->attendances_count
            ),
            'passed_count' =>$this->when(
                $this->passed_count,
                $this->passed_count
            ),
            'pivot' => $this->when(
                $this->pivot,
                $this->pivot
            ),
        ];

    }
}
