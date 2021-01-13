<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\HasFetchAllRenderCapabilities;
use App\Http\Requests\ActorMovieRoleRequest;
use App\Http\Resources\ActorMovieRoleCollection;
use App\Models\ActorMovieRole;
use Illuminate\Http\Request;

class ActorMovieRoleController extends Controller
{
    use HasFetchAllRenderCapabilities;

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return ActorMovieRoleCollection
     */
    public function index(Request $request)
    {
        $this->setGetAllBuilder(ActorMovieRole::query()->with(['actor', 'movie']));
        $this->setGetAllOrdering('name', 'asc');
        $this->parseRequestConditions($request);
        return new ActorMovieRoleCollection($this->getAll()->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ActorMovieRoleRequest $request
     * @return \App\Http\Resources\ActorMovieRole
     */
    public function store(ActorMovieRoleRequest $request)
    {
        $actorMovieRole = new ActorMovieRole($request->validated());
        $actorMovieRole->save();

        return (new \App\Http\Resources\ActorMovieRole($actorMovieRole))
            ->withRelations(['actor', 'movie']);
    }

    /**
     * Display the specified resource.
     *
     * @param ActorMovieRole $actorMovieRole
     * @return \App\Http\Resources\ActorMovieRole
     */
    public function show(ActorMovieRole $actorMovieRole)
    {
        return (new \App\Http\Resources\ActorMovieRole($actorMovieRole))
            ->withRelations(['actor', 'movie']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ActorMovieRoleRequest $request
     * @param ActorMovieRole $actorMovieRole
     * @return \App\Http\Resources\ActorMovieRole
     */
    public function update(ActorMovieRoleRequest $request, ActorMovieRole $actorMovieRole)
    {
        $actorMovieRole->update($request->validated());

        return (new \App\Http\Resources\ActorMovieRole($actorMovieRole))
            ->withRelations(['actor', 'movie']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ActorMovieRole $actorMovieRole
     * @return \Illuminate\Http\Response
     */
    public function destroy(ActorMovieRole $actorMovieRole)
    {
        $actorMovieRole->delete();

        return response()->noContent();
    }
}
