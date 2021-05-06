<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

class GroupCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => GroupResource::collection($this->collection),
            'links' => [
                'self' => 'link-value',
            ],
        ];
    }
}
