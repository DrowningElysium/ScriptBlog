<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $blog = [
            'id' => $this->id,
            'writer' => WriterResource::make($this->whenLoaded('writer')),
            'title' => $this->title,
            'content' => $this->content,
            'tags' => BlogTagCollection::make($this->whenLoaded('tags')),
            'premium' => $this->premium,
            'published_at' => $this->published_at->toIso8601String()
        ];
        return $blog;
    }
}
