<?php

namespace Tests\Feature;

use App\Http\Resources\Actor as ActorResource;
use App\Http\Resources\ActorCollection;
use App\Models\Actor;
use App\Models\ActorMovieRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;
use Tests\Traits\WithResourceValidation;

class ActorTest extends TestCase
{
    use WithResourceValidation, RefreshDatabase;

    private $resourceStructure = [
        'data' => [
            'id',
            'name',
            'bio',
            'born_at',
        ],
    ];

    private $resourceCollectionStructure = [
        'data' => [
            '*' => [
                'id',
                'name',
                'bio',
                'born_at',
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

    public function test_can_list_actors()
    {
        $this->seed(\ActorSeeder::class);

        $response = $this->getJson(route('actors.index'));

        $actors = Actor::query()
            ->orderBy('name', 'asc')
            ->paginate();

        $response->assertStatus(200);
        $response->assertJsonStructure($this->resourceCollectionStructure);
        $this->validateResourceResponse($response, new ActorCollection($actors));
    }

    public function test_can_show_actor()
    {
        $actor = factory(Actor::class)->create();

        $response = $this->getJson(route('actors.show', $actor));

        $response->assertStatus(200);
        $response->assertJsonStructure($this->resourceStructure);
        $this->validateResourceResponse($response, new ActorResource($actor));
    }

    public function test_can_create_actor()
    {
        $data = factory(Actor::class)->make()->toArray();

        $response = $this->postJson(route('actors.store'), $data);

        $response->assertStatus(201);
        $response->assertJsonStructure($this->resourceStructure);
        $response->assertJsonFragment(Arr::only($data, $this->resourceStructure['data']));
    }

    public function test_can_update_actor()
    {
        $actor = factory(Actor::class)->create();

        $data = $actor
            ->replicate()
            ->fill(['name' => 'Updated Actor Name'])
            ->toArray();

        $response = $this->putJson(route('actors.update', $actor), $data);

        $actor->refresh();

        $response->assertStatus(200);
        $response->assertJsonStructure($this->resourceStructure);
        $response->assertJsonFragment(Arr::only($data, $this->resourceStructure['data']));
        $this->validateResourceResponse($response, new ActorResource($actor));
    }

    public function test_can_delete_actor()
    {
        $actor = factory(Actor::class)->create();

        $response = $this->deleteJson(route('actors.destroy', $actor));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('actors', ['id' => $actor->id]);
    }

    public function test_can_list_movies_that_an_actor_starred_on()
    {
        $actor = factory(Actor::class)->create();

        factory(ActorMovieRole::class, 4)->create([
           'actor_id' => $actor->id,
        ]);

        $response = $this->getJson(route('actors.movies', $actor));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'name',
                    'year',
                    'synopsis',
                    'runtime',
                    'released_at',
                    'cost',
                ]
            ],
        ]);
    }
}
