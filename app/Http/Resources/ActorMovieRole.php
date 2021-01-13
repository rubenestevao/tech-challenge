<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActorMovieRole extends JsonResource
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
            'name' => $this->resource->name,
            'actor' => new Actor($this->whenLoaded('actor')),
            'movie' => new Movie($this->whenLoaded('movie')),
        ];
    }

    /**
     * @param  array|string  $relations
     * @return $this
     */
    public function withRelations($relations)
    {
        $this->resource->loadMissing($relations);

        return $this;
    }
}
