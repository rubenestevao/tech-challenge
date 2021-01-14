<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\Genre::class, function (Faker $faker) {
    return [
        'id' => $faker->uuid,
        'name' => $faker->unique()->colorName,
        'created_at'    => now(),
    ];
});
