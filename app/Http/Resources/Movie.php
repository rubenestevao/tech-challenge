<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Movie extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'year' => $this->resource->year,
            'synopsis' => $this->resource->synopsis,
            'runtime' => $this->resource->runtime,
            'released_at' => $this->resource->released_at,
            'cost' => $this->resource->cost,
            'genre' => new Genre($this->whenLoaded('genre')),
        ];
    }
}
