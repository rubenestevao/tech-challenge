<?php

use App\Models\Actor;
use App\Models\ActorMovieRole;
use App\Models\Movie;
use Illuminate\Database\Seeder;

class ActorMovieRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Movie::all() as $movie) {
            factory(ActorMovieRole::class, 5)->create([
                'actor_id' => function () {
                    return $this->getRandomActor()->id;
                },
                'movie_id' => $movie->id,
            ]);
        }
    }

    private function getRandomActor()
    {
        return Actor::query()
            ->inRandomOrder()
            ->first();
    }
}
