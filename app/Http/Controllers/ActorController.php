<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\HasFetchAllRenderCapabilities;
use App\Http\Requests\ActorRequest;
use App\Http\Resources\ActorCollection;
use App\Models\Actor;
use Illuminate\Http\Request;

class ActorController extends Controller
{
    use HasFetchAllRenderCapabilities;

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return ActorCollection
     */
    public function index(Request $request)
    {
        $this->setGetAllBuilder(Actor::query());
        $this->setGetAllOrdering('name', 'asc');
        $this->parseRequestConditions($request);
        return new ActorCollection($this->getAll()->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ActorRequest $request
     * @return \App\Http\Resources\Actor
     */
    public function store(ActorRequest $request)
    {
        $actor = new Actor($request->validated());
        $actor->save();

        return new \App\Http\Resources\Actor($actor);
    }

    /**
     * Display the specified resource.
     *
     * @param Actor $actor
     * @return \App\Http\Resources\Actor
     */
    public function show(Actor $actor)
    {
        return new \App\Http\Resources\Actor($actor);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ActorRequest $request
     * @param Actor $actor
     * @return \App\Http\Resources\Actor
     */
    public function update(ActorRequest $request, Actor $actor)
    {
        $actor->update($request->validated());

        return new \App\Http\Resources\Actor($actor);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Actor $actor
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Actor $actor)
    {
        $actor->delete();

        return response()->noContent();
    }
}
