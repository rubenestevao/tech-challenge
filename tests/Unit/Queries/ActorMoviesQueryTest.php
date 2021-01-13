<?php

namespace Tests\Unit\Queries;

use App\Models\Actor;
use App\Models\ActorMovieRole;
use App\Models\Movie;
use App\Queries\ActorMoviesQuery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActorMoviesQueryTest extends TestCase
{
    use RefreshDatabase;

    public function test_fetch_movies_that_an_actor_starred_on()
    {
        $actor = factory(Actor::class)->create();
        $movies = factory(Movie::class, 3)->create();

        foreach ($movies as $movie) {
            factory(ActorMovieRole::class)->create([
                'actor_id' => $actor->id,
                'movie_id' => $movie->id,
            ]);
        }

        $queryResult = (new ActorMoviesQuery($actor))
            ->getQuery()
            ->get()
            ->toArray();

        $this->assertEquals($movies->toArray(), $queryResult);
    }

    public function test_fetch_movies_that_an_actor_starred_on_with_multiple_roles()
    {
        $actor = factory(Actor::class)->create();
        $movies = factory(Movie::class, 3)->create();

        foreach ($movies as $movie) {
            factory(ActorMovieRole::class, 2)->create([
                'actor_id' => $actor->id,
                'movie_id' => $movie->id,
            ]);
        }

        $queryResult = (new ActorMoviesQuery($actor))
            ->getQuery()
            ->get()
            ->toArray();

        $this->assertEquals($movies->toArray(), $queryResult);
    }

    public function test_fetch_empty_when_an_actor_did_not_star_in_any_movie()
    {
        $actor = factory(Actor::class)->create();
        factory(Movie::class, 3)->create();

        $queryResult = (new ActorMoviesQuery($actor))
            ->getQuery()
            ->get()
            ->toArray();

        $this->assertEquals([], $queryResult);
    }
}
