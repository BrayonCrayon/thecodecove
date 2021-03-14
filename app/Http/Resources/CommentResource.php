<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'text' => $this->resource->text,
            'post_id' => $this->resource->post_id,
            'user_id' => $this->resource->user_id,
            'parent_id' => $this->resource->parent_id,
        ];
    }
}
