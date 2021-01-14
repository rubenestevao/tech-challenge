<?php

namespace Tests\Feature;

use App\Http\Resources\Genre as GenreResource;
use App\Http\Resources\GenreCollection;
use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;
use Tests\Traits\WithResourceValidation;

class GenreTest extends TestCase
{
    use WithResourceValidation, RefreshDatabase;

    private $resourceStructure = [
        'data' => [
            'id',
            'name',
        ],
    ];

    private $resourceCollectionStructure = [
        'data' => [
            '*' => [
                'id',
                'name',
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

    public function test_can_list_genres()
    {
        $this->seed(\GenreSeeder::class);

        $response = $this->getJson(route('genres.index'));

        $genres = Genre::query()
            ->orderBy('name', 'asc')
            ->paginate();

        $response->assertStatus(200);
        $response->assertJsonStructure($this->resourceCollectionStructure);
        $this->validateResourceResponse($response, new GenreCollection($genres));
    }

    public function test_can_show_genre()
    {
        $genre = factory(Genre::class)->create();

        $response = $this->getJson(route('genres.show', $genre));

        $response->assertStatus(200);
        $response->assertJsonStructure($this->resourceStructure);
        $this->validateResourceResponse($response, new GenreResource($genre));
    }

    public function test_can_create_genre()
    {
        $data = Arr::except(factory(Genre::class)->make()->toArray(), 'id');

        $response = $this->postJson(route('genres.store'), $data);

        $response->assertStatus(201);
        $response->assertJsonStructure($this->resourceStructure);
        $response->assertJsonFragment(Arr::only($data, $this->resourceStructure['data']));
    }

    public function test_can_update_genre()
    {
        $genre = factory(Genre::class)->create();

        $data = $genre
            ->replicate()
            ->fill(['name' => 'Updated Genre Name'])
            ->toArray();

        $response = $this->putJson(route('genres.update', $genre), $data);

        $genre->refresh();

        $response->assertStatus(200);
        $response->assertJsonStructure($this->resourceStructure);
        $response->assertJsonFragment(Arr::only($data, $this->resourceStructure['data']));
        $this->validateResourceResponse($response, new GenreResource($genre));
    }

    public function test_can_delete_genre()
    {
        $genre = factory(Genre::class)->create();
        $response = $this->deleteJson(route('genres.destroy', $genre));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('genres', ['id' => $genre->id]);

    }

    public function test_can_list_actors_that_belong_to_a_genre()
    {
        $genre = factory(Genre::class)->create();

        factory(Movie::class)->create([
            'genre_id' => $genre->id,
        ]);

        $this->seed(\ActorSeeder::class);
        $this->seed(\ActorMovieRoleSeeder::class);

        $response = $this->getJson(route('genres.actors', $genre));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'name',
                    'bio',
                    'born_at',
                    'appearances',
                ]
            ],
        ]);
    }
}
