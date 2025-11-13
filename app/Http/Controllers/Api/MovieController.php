<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TmdbService;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    protected $tmdb;

    public function __construct(TmdbService $tmdb)
    {
        $this->tmdb = $tmdb;
    }

    /**
     * Obtener películas populares
     */
    public function popular(Request $request)
    {
        $page = $request->query('page', 1);
        $movies = $this->tmdb->getPopularMovies($page);

        if (!$movies) {
            return response()->json([
                'error' => 'No se pudieron obtener las películas'
            ], 500);
        }

        return response()->json($movies);
    }

    /**
     * Obtener películas más valoradas
     */
    public function topRated(Request $request)
    {
        $page = $request->query('page', 1);
        $movies = $this->tmdb->getTopRatedMovies($page);

        if (!$movies) {
            return response()->json([
                'error' => 'No se pudieron obtener las películas'
            ], 500);
        }

        return response()->json($movies);
    }

    /**
     * Obtener próximos estrenos
     */
    public function upcoming(Request $request)
    {
        $page = $request->query('page', 1);
        $movies = $this->tmdb->getUpcomingMovies($page);

        if (!$movies) {
            return response()->json([
                'error' => 'No se pudieron obtener las películas'
            ], 500);
        }

        return response()->json($movies);
    }

    /**
     * Obtener detalles de la película
     */
    public function show($id)
    {
        $movie = $this->tmdb->getMovie($id);

        if (!$movie) {
            return response()->json([
                'error' => 'No se pudo obtener la película'
            ], 404);
        }

        return response()->json($movie);
    }

    /**
     * Buscar películas
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:1'
        ]);

        $query = $request->query('query');
        $page = $request->query('page', 1);

        $results = $this->tmdb->searchMovies($query, $page);

        if (!$results) {
            return response()->json([
                'error' => 'No se pudo realizar la búsqueda'
            ], 500);
        }

        return response()->json($results);
    }

    /**
     * Obtener películas similares
     */
    public function similar($id, Request $request)
    {
        $page = $request->query('page', 1);
        $movies = $this->tmdb->getSimilarMovies($id, $page);

        if (!$movies) {
            return response()->json([
                'error' => 'No se pudieron obtener películas similares'
            ], 500);
        }

        return response()->json($movies);
    }
}
