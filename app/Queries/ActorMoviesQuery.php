<?php

namespace App\Queries;

use App\Models\Actor;
use App\Models\Movie;
use Illuminate\Database\Eloquent\Builder;

class ActorMoviesQuery
{
    protected $actor;

    public function __construct(Actor $actor)
    {
        $this->actor = $actor;
    }

    public function getQuery(): Builder
    {
        return Movie::query()
            ->whereHas('roles', function ($query) {
                $query->where('actor_id', $this->actor->id);
            });
    }
}
