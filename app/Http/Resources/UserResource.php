<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        $role = $this->when(
            $this->relationLoaded('role'),
            $this->role
        );

        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->first_name . ' ' . $this->last_name,
            'email' => $this->email,
            'role' => $this->when(
                $role,
                $role
            ),
            'role_name' => $this->when(
                $role,
                data_get($this, 'role.name')
            ),
        ];

    }
}
