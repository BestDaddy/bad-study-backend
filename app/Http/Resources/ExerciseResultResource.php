<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ExerciseResultResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'exercise_id'=> $this->exercise_id,
            'score' => $this->score,
            'value' => $this->value,
            'comment'=> $this->comment,
            'checked_at'=> $this->checked_at,
            'created_at'=> $this->created_at,
            'updated_at' => $this->updated_at,
            'attachments' => $this->when(
                $this->relationLoaded('attachments'),
                $this->attachments
            ),
            'exercise' => $this->when(
                $this->relationLoaded('exercise'),
                ExerciseResource::make($this->exercise)
            ),
        ];
    }
}
