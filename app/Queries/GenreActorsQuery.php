<?php

namespace App\Queries;

use App\Models\Actor;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Builder;

class GenreActorsQuery
{
    protected Genre $genre;

    public function __construct(Genre $genre)
    {
        $this->genre = $genre;
    }

    public function getQuery(): Builder
    {
        return Actor::query()
            ->withCount([
                'roles' => function ($query) {
                    $query->whereHas('movie', function ($query) {
                        $query->where('genre_id', $this->genre->id);
                    });
                }
            ])
            ->where('roles_count', '>', 0)
            ->orderBy('roles_count', 'desc');
    }
}
