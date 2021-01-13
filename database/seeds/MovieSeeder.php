<?php

use App\Models\Movie;
use Illuminate\Database\Seeder;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Movie::class, 10)
            ->create([
                'genre_id' => $this->getRandomGenre(),
            ]);
    }

    private function getRandomGenre()
    {
        return \App\Models\Genre::query()
            ->inRandomOrder()
            ->first();
    }
}
