<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TmdbService;
use Illuminate\Http\Request;

class CineVerseController extends Controller
{
    protected $tmdb;

    public function __construct(TmdbService $tmdb)
    {
        $this->tmdb = $tmdb;
    }

    /**
     * Obtener series populares
     */
    public function popular(string $type, Request $request )
    {

        return response()->json([
            'type' => $type,
        ]);
    }

    /**
     * Obtener series mejor valoradas
     */
    public function topRated(Request $request)
    {
        $page = $request->query('page', 1);
        $shows = $this->tmdb->getTopRatedTVShows($page);

        if (!$shows) {
            return response()->json([
                'error' => 'No se pudieron obtener las series'
            ], 500);
        }

        return response()->json($shows);
    }

    /**
     * Obtener series al aire
     */


    /**
     * Obtener detalles de la serie
     */
    public function show($type, $tmdbId)
    {
        $tmdbService = new TmdbService();
        $tmdbData = $tmdbService->getCineverse($type, $tmdbId);
        $title = $tmdbData['title'] ?? $tmdbData['name'];

        $data =  [
            'backdrop_path' => $tmdbData['backdrop_path'],
            'title' => $title,
        ];




        return response()->json($data);
    }

    /**
     * Buscar series
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:1'
        ]);

        $query = $request->query('query');
        $page = $request->query('page', 1);

        $results = $this->tmdb->searchTVShows($query, $page);

        if (!$results) {
            return response()->json([
                'error' => 'No se pudo realizar la búsqueda'
            ], 500);
        }

        return response()->json($results);
    }

    /**
     * Obtener series similares
     */
    public function similar($id, Request $request)
    {
        $page = $request->query('page', 1);
        $shows = $this->tmdb->getSimilarTVShows($id, $page);

        if (!$shows) {
            return response()->json([
                'error' => 'No se pudieron obtener series similares'
            ], 500);
        }

        return response()->json($shows);
    }

    public function getProviders($id)
    {
        try {
            $response = $this->tmdb->getTVProviders($id);  // ← Solo el ID

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
     * Obtener géneros de series
     */
    public function genres()
    {
        try {
            $genres = $this->tmdb->getTVGenres();

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
     * Descubrir series (con filtros)
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
                $params['first_air_date_year'] = $request->query('year');
            }

            $shows = $this->tmdb->discoverTV($params);

            if (!$shows) {
                return response()->json([
                    'error' => 'No se pudieron obtener series'
                ], 500);
            }

            return response()->json($shows);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener series',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
