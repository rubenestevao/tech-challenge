<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\HasFetchAllRenderCapabilities;
use App\Http\Requests\MovieRequest as MovieRequest;
use App\Http\Resources\MovieCollection;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MovieController extends Controller
{
    use HasFetchAllRenderCapabilities;

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return MovieCollection
     */
    public function index(Request $request)
    {
        $this->setGetAllBuilder(Movie::query()->with('genre'));
        $this->setGetAllOrdering('name', 'asc');
        $this->parseRequestConditions($request);
        return new MovieCollection($this->getAll()->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param MovieRequest $request
     * @return \App\Http\Resources\Movie
     */
    public function store(MovieRequest $request)
    {
        $movie = new Movie($request->validated());
        $movie->save();

        return new \App\Http\Resources\Movie($movie);
    }

    /**
     * Display the specified resource.
     *
     * @param Movie $movie
     * @return \App\Http\Resources\Movie
     */
    public function show(Movie $movie)
    {
        return new \App\Http\Resources\Movie($movie);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MovieRequest $request
     * @param Movie $movie
     * @return \App\Http\Resources\Movie
     */
    public function update(MovieRequest $request, Movie $movie)
    {
        $movie->update($request->validated());

        return new \App\Http\Resources\Movie($movie);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Movie $movie
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Movie $movie)
    {
        $movie->delete();

        return response()->noContent();
    }
}
