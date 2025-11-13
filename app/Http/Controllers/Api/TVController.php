<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TmdbService;
use Illuminate\Http\Request;

class TVController extends Controller
{
    protected $tmdb;

    public function __construct(TmdbService $tmdb)
    {
        $this->tmdb = $tmdb;
    }

    /**
     * Obtener series populares
     */
    public function popular(Request $request)
    {
        $page = $request->query('page', 1);
        $shows = $this->tmdb->getPopularTVShows($page);

        if (!$shows) {
            return response()->json([
                'error' => 'No se pudieron obtener las series'
            ], 500);
        }

        return response()->json($shows);
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
    public function onTheAir(Request $request)
    {
        $page = $request->query('page', 1);
        $shows = $this->tmdb->getOnTheAirTVShows($page);

        if (!$shows) {
            return response()->json([
                'error' => 'No se pudieron obtener las series'
            ], 500);
        }

        return response()->json($shows);
    }

    /**
     * Obtener detalles de la serie
     */
    public function show($id)
    {
        $show = $this->tmdb->getTVShow($id);

        if (!$show) {
            return response()->json([
                'error' => 'No se pudo obtener la serie'
            ], 404);
        }

        return response()->json($show);
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
}
