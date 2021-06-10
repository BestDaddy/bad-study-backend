<?php


namespace App\Http\Resources;


use App\Models\ExerciseResult;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ExerciseResultResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'exercise_id'=> $this->exercise_id,
            'status' => ExerciseResult::getStatusTexts()[$this->status],
            'score' => number_format($this->score, 1),
            'value' => $this->value,
            'comment'=> $this->comment,
            'checked_at'=> Carbon::parse($this->checked_at)->diffForHumans(),
            'submited_at' =>Carbon::parse($this->checked_at)->format('d M H:i '),
            'updated_at'=> Carbon::parse($this->updated_at)->diffForHumans(),
            'attachments' => $this->when(
                $this->relationLoaded('attachments'),
                AttachmentResource::collection($this->attachments)
            ),
            'exercise' => $this->when(
                $this->relationLoaded('exercise'),
                ExerciseResource::make($this->exercise)
            ),
            'user' => $this->when(
                $this->relationLoaded('user'),
                UserResource::make($this->user)
            ),
        ];
    }
}
