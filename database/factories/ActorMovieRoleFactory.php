<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Actor;
use App\Models\ActorMovieRole;
use App\Models\Movie;
use Faker\Generator as Faker;

$factory->define(ActorMovieRole::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'actor_id' => factory(Actor::class),
        'movie_id' => factory(Movie::class),
    ];
});
