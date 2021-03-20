<?php


namespace App\Http\Resource;


use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            )
        ];

    }
}
