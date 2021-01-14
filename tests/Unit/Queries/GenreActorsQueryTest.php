<?php

namespace Tests\Unit\Queries;

use App\Models\Actor;
use App\Models\ActorMovieRole;
use App\Models\Genre;
use App\Models\Movie;
use App\Queries\GenreActorsQuery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class GenreActorsQueryTest extends TestCase
{
    use RefreshDatabase;

    public function test_fetch_actors_that_starred_in_a_given_genre()
    {
        $genre = factory(Genre::class)->create();

        $movie = factory(Movie::class)->create([
            'genre_id' => $genre->id,
        ]);

        $actors = factory(Actor::class, 3)
            ->create()
            ->each(function ($actor) use ($movie) {
                factory(ActorMovieRole::class)->create([
                    'actor_id' => $actor->id,
                    'movie_id' => $movie->id,
                ]);

                $actor->loadCount('roles');
            });

        $queryResult = (new GenreActorsQuery($genre))
            ->getQuery()
            ->get()
            ->toArray();

        $this->assertEquals($actors->toArray(), $queryResult);
    }

    public function test_genre_actors_query_result_returns_actors_ordered_by_movie_appearances()
    {
        $genre = factory(Genre::class)->create();

        $movie = factory(Movie::class)->create([
            'genre_id' => $genre->id,
        ]);

        $firstActor = factory(Actor::class)->create(['name' => 'Actor 1']);

        factory(ActorMovieRole::class)->create([
            'actor_id' => $firstActor->id,
            'movie_id' => $movie->id,
        ]);

        $secondActor = factory(Actor::class)->create(['name' => 'Actor 2']);

        factory(ActorMovieRole::class, 3)->create([
            'actor_id' => $secondActor->id,
            'movie_id' => $movie->id,
        ]);

        $thirdActor = factory(Actor::class)->create(['name' => 'Actor 3']);

        factory(ActorMovieRole::class, 2)->create([
            'actor_id' => $thirdActor->id,
            'movie_id' => $movie->id,
        ]);

        $queryResult = (new GenreActorsQuery($genre))
            ->getQuery()
            ->get()
            ->toArray();

        $this->assertEquals(['Actor 2', 'Actor 3', 'Actor 1'], Arr::pluck($queryResult, 'name'));
    }

    public function test_genre_actors_query_result_returns_only_appearances_in_the_same_genre()
    {
        $genre = factory(Genre::class)->create([
            'name' => 'comedy'
        ]);

        $movie = factory(Movie::class)->create([
            'name' => 'The Nutty Professor',
            'genre_id' => $genre->id,
        ]);

        $firstActor = factory(Actor::class)->create(['name' => 'Eddie Murphy']);

        factory(ActorMovieRole::class, 5)->create([
            'actor_id' => $firstActor->id,
            'movie_id' => $movie->id,
        ]);

        factory(ActorMovieRole::class, 2)->create([
            'actor_id' => $firstActor->id,
        ]);

        $secondActor = factory(Actor::class)->create(['name' => 'Jada Pinkett Smith']);

        factory(ActorMovieRole::class, 1)->create([
            'actor_id' => $secondActor->id,
            'movie_id' => $movie->id,
        ]);

        factory(ActorMovieRole::class, 3)->create([
            'actor_id' => $secondActor->id,
        ]);

        $queryResult = (new GenreActorsQuery($genre))
            ->getQuery()
            ->get()
            ->toArray();

        $this->assertEquals([5, 1], Arr::pluck($queryResult, 'roles_count'));
    }
}
