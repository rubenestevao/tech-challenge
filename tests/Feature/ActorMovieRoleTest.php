<?php

namespace Tests\Feature;

use App\Http\Resources\ActorMovieRole as ActorMovieRoleResource;
use App\Http\Resources\ActorMovieRoleCollection;
use App\Models\ActorMovieRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;
use Tests\Traits\WithResourceValidation;

class ActorMovieRoleTest extends TestCase
{
    use WithResourceValidation, RefreshDatabase;

    private $resourceStructure = [
        'data' => [
            'name',
            'actor',
            'movie',
        ],
    ];

    private $resourceCollectionStructure = [
        'data' => [
            '*' => [
                'name',
                'actor',
                'movie',
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

    public function test_can_list_actor_movie_roles()
    {
        $this->seed(\GenreSeeder::class);
        $this->seed(\MovieSeeder::class);
        $this->seed(\ActorSeeder::class);
        $this->seed(\ActorMovieRoleSeeder::class);

        $response = $this->getJson(route('actor-movie-roles.index'));

        $actorMovieRoles = ActorMovieRole::query()
            ->with(['actor', 'movie'])
            ->orderBy('name', 'asc')
            ->paginate();

        $response->assertStatus(200);
        $response->assertJsonStructure($this->resourceCollectionStructure);
        $this->validateResourceResponse($response, new ActorMovieRoleCollection($actorMovieRoles));
    }

    public function test_can_show_actor_movie_role()
    {
        $actorMovieRole = factory(ActorMovieRole::class)->create();

        $response = $this->getJson(route('actor-movie-roles.show', $actorMovieRole));

        $response->assertStatus(200);
        $response->assertJsonStructure($this->resourceStructure);
        $this->validateResourceResponse($response, new ActorMovieRoleResource($actorMovieRole));
    }

    public function test_can_create_actor_movie_role()
    {
        $data = factory(ActorMovieRole::class)->make()->toArray();

        $response = $this->postJson(route('actor-movie-roles.store'), $data);

        $response->assertStatus(201);
        $response->assertJsonStructure($this->resourceStructure);
        $response->assertJsonFragment(Arr::only($data, $this->resourceStructure['data']));
    }

    public function test_can_update_actor_movie_role()
    {
        $actorMovieRole = factory(ActorMovieRole::class)->create();

        $data = $actorMovieRole
            ->replicate()
            ->fill(['name' => 'Updated Role Name'])
            ->toArray();

        $response = $this->putJson(route('actor-movie-roles.update', $actorMovieRole), $data);

        $actorMovieRole->refresh();

        $response->assertStatus(200);
        $response->assertJsonStructure($this->resourceStructure);
        $response->assertJsonFragment(Arr::only($data, $this->resourceStructure['data']));
        $this->validateResourceResponse($response, new ActorMovieRoleResource($actorMovieRole));
    }

    public function test_can_delete_actor_movie_role()
    {
        $actorMovieRole = factory(ActorMovieRole::class)->create();

        $response = $this->deleteJson(route('actor-movie-roles.destroy', $actorMovieRole));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('actor_movie_roles', ['id' => $actorMovieRole->id]);
    }
}
