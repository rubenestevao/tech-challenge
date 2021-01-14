<?php

namespace App\Http\Resources;

use App\Http\Resources\Traits\InteractsWithResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ActorMovieRole extends JsonResource
{
    use InteractsWithResource;

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
            'actor' => new Actor($this->whenLoaded('actor')),
            'movie' => new Movie($this->whenLoaded('movie')),
        ];
    }
}
