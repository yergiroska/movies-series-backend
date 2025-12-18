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

    public function getProviders($id)
    {
        try {
            $response = $this->tmdb->getMovieProviders($id);  // ← Solo el ID

            if (!$response) {
                return response()->json([
                    'error' => 'No se pudieron obtener proveedores'
                ], 500);
            }

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No se pudieron obtener los proveedores',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener géneros de películas
     */
    public function genres()
    {
        try {
            $genres = $this->tmdb->getMovieGenres();

            if (!$genres) {
                return response()->json([
                    'error' => 'No se pudieron obtener los géneros'
                ], 500);
            }

            return response()->json($genres);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener géneros',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Descubrir películas (con filtros)
     */
    public function discover(Request $request)
    {
        try {
            $params = [
                'page' => $request->query('page', 1),
            ];

            // Filtro por género
            if ($request->has('genre')) {
                $params['with_genres'] = $request->query('genre');
            }

            // Filtro por año
            if ($request->has('year')) {
                $params['primary_release_year'] = $request->query('year');
            }

            $movies = $this->tmdb->discoverMovies($params);

            if (!$movies) {
                return response()->json([
                    'error' => 'No se pudieron obtener películas'
                ], 500);
            }

            return response()->json($movies);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener películas',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
