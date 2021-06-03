<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ExerciseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'chapter_id' => $this->chapter_id,
            'name' => $this->name,
            'content' => $this->content,
            'path' => $this->path,
            'order' => $this->order,
            'result' => $this->when(
                $this->relationLoaded('result'),
                ExerciseResultResource::make($this->result)
            ),
            'attachments' => $this->when(
                $this->relationLoaded('attachments'),
                AttachmentResource::collection($this->attachments)
            ),
            'attachment' => $this->when(
                $this->relationLoaded('attachment'),
                AttachmentResource::make($this->attachment)
            ),
            'results_count' => $this->when(
                $this->results_count,
                $this->results_count
            ),
            'not_checked' => $this->when(
                $this->not_checked,
                $this->not_checked
            ),
        ];
    }
}
