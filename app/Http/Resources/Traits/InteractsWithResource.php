<?php

namespace App\Http\Resources\Traits;

trait InteractsWithResource
{
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
