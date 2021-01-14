<?php

namespace Tests\Feature;

use App\Http\Resources\Movie as MovieResource;
use App\Http\Resources\MovieCollection;
use App\Models\Movie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;
use Tests\Traits\WithResourceValidation;

class MovieTest extends TestCase
{
    use WithResourceValidation, RefreshDatabase;

    private $resourceStructure = [
        'data' => [
            'id',
            'name',
            'year',
            'synopsis',
            'runtime',
            'released_at',
            'cost'
        ],
    ];

    private $resourceCollectionStructure = [
        'data' => [
            '*' => [
                'id',
                'name',
                'year',
                'synopsis',
                'runtime',
                'released_at',
                'cost',
            ]
        ],
        'links' => [
            'first',
            'last',
            'prev',
            'next',
        ],
        'meta' => [
            'current_page',
            'from',
            'last_page',
            'path',
        ],
    ];

    public function test_can_list_movies()
    {
        $this->seed(\GenreSeeder::class);
        $this->seed(\MovieSeeder::class);

        $response = $this->getJson(route('movies.index'));

        $movies = Movie::query()
            ->with('genre')
            ->orderBy('name')
            ->paginate();

        $response->assertStatus(200);
        $response->assertJsonStructure($this->resourceCollectionStructure);
        $this->validateResourceResponse($response, new MovieCollection($movies));
    }

    public function test_can_show_movie()
    {
        $movie = factory(Movie::class)->create();

        $response = $this->getJson(route('movies.show', $movie));

        $response->assertStatus(200);
        $response->assertJsonStructure($this->resourceStructure);
        $this->validateResourceResponse($response, new MovieResource($movie));
    }

    public function test_can_create_movie()
    {
        $data = factory(Movie::class)->make()->toArray();

        $response = $this->postJson(route('movies.store'), $data);

        $response->assertStatus(201);
        $response->assertJsonStructure($this->resourceStructure);
        $response->assertJsonFragment(Arr::only($data, $this->resourceStructure['data']));
    }

    public function test_can_update_movie()
    {
        $movie = factory(Movie::class)->create();

        $data = $movie
            ->replicate()
            ->fill([
                'name' => 'Updated Movie Name'
            ])
            ->toArray();

        $response = $this->putJson(route('movies.update', $movie), $data);

        $movie->refresh();

        $response->assertStatus(200);
        $response->assertJsonStructure($this->resourceStructure);
        $response->assertJsonFragment(Arr::only($data, $this->resourceStructure['data']));
        $this->validateResourceResponse($response, new MovieResource($movie));
    }

    public function test_can_delete_movie()
    {
        $movie = factory(Movie::class)->create();

        $response = $this->deleteJson(route('movies.destroy', $movie));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('movies', ['id' => $movie->id]);
    }
}
