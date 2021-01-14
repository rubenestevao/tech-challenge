<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::name('actors.movies')->get('actors/{actor}/movies',  ActorController::class . '@movies');
Route::name('genres.actors')->get('genres/{genre}/actors',  GenreController::class . '@actors');

Route::resources([
    'genres' => GenreController::class,
    'movies' => MovieController::class,
    'actors' => ActorController::class,
    'actor-movie-roles' => ActorMovieRoleController::class,
]);
