<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TmdbService
{
    protected $apiKey;
    protected $baseUrl;
    protected $imageBaseUrl;

    public function __construct()
    {
        $this->apiKey = config('tmdb.api_key');
        $this->baseUrl = config('tmdb.base_url');
        $this->imageBaseUrl = config('tmdb.image_base_url');
    }

    /**
     * Realizar petición GET a TMDB
     */
    private function get($endpoint, $params = [])
    {
        try {
            $params['api_key'] = $this->apiKey;
            $params['language'] = 'es-ES';

            $response = Http::get("{$this->baseUrl}/{$endpoint}", $params);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('TMDB API Error', [
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('TMDB Service Exception', [
                'message' => $e->getMessage(),
                'endpoint' => $endpoint
            ]);
            return null;
        }
    }

    // ==================== MOVIES ====================

    /**
     * Obtener películas populares
     */
    public function getPopularMovies($page = 1)
    {
        return $this->get('movie/popular', ['page' => $page]);
    }

    /**
     * Obtener películas mejor valoradas
     */
    public function getTopRatedMovies($page = 1)
    {
        return $this->get('movie/top_rated', ['page' => $page]);
    }

    /**
     * Obtener próximos estrenos
     */
    public function getUpcomingMovies($page = 1)
    {
        return $this->get('movie/upcoming', ['page' => $page]);
    }

    /**
     * Obtener detalle de película
     */
    public function getMovie($id)
    {
        return $this->get("movie/{$id}");
    }

    /**
     * Buscar películas
     */
    public function searchMovies($query, $page = 1)
    {
        return $this->get('search/movie', [
            'query' => $query,
            'page' => $page
        ]);
    }

    /**
     * Obtener películas similares
     */
    public function getSimilarMovies($id, $page = 1)
    {
        return $this->get("movie/{$id}/similar", ['page' => $page]);
    }

    // ==================== TV SHOWS ====================

    /**
     * Obtener series populares
     */
    public function getPopularTVShows($page = 1)
    {
        return $this->get('tv/popular', ['page' => $page]);
    }

    /**
     * Obtener series mejor valoradas
     */
    public function getTopRatedTVShows($page = 1)
    {
        return $this->get('tv/top_rated', ['page' => $page]);
    }

    /**
     * Obtener series al aire
     */
    public function getOnTheAirTVShows($page = 1)
    {
        return $this->get('tv/on_the_air', ['page' => $page]);
    }

    /**
     * Obtener detalle de serie
     */
    public function getTVShow($id)
    {
        return $this->get("tv/{$id}");
    }

    /**
     * Buscar series
     */
    public function searchTVShows($query, $page = 1)
    {
        return $this->get('search/tv', [
            'query' => $query,
            'page' => $page
        ]);
    }

    /**
     * Obtener series similares
     */
    public function getSimilarTVShows($id, $page = 1)
    {
        return $this->get("tv/{$id}/similar", ['page' => $page]);
    }

    // ==================== SEARCH ====================

    /**
     * Búsqueda multi (películas y series)
     */
    public function multiSearch($query, $page = 1)
    {
        return $this->get('search/multi', [
            'query' => $query,
            'page' => $page
        ]);
    }

    // ==================== HELPERS ====================

    /**
     * Obtener URL completa de imagen
     */
    public function getImageUrl($path, $size = 'w500')
    {
        if (!$path) {
            return null;
        }
        return $this->imageBaseUrl . $size . $path;
    }
}
