<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'content' => $this->resource->content,
            'user_id' => $this->resource->user_id,
            'status_id' => $this->resource->status_id,
            'published_at' => $this->resource->published_at,
            'created_at' => $this->resource->created_at,
        ];
    }
}
