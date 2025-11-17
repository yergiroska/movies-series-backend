<?php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\WatchlistController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí puedes registrar las rutas de API para tu aplicación. Estas
| rutas son cargadas por RouteServiceProvider y todas tendrán el
| prefijo "api" y el middleware "api" aplicado automáticamente.
|
*/

// Ruta de prueba TMDB (temporal)
Route::get('/tmdb-test', function () {
    $tmdb = new \App\Services\TmdbService();

    $popular = $tmdb->getPopularTVShows();

    return response()->json([
        'status' => 'success',
        'message' => 'TMDB API funcionando correctamente',
        'sample_data' => $popular
    ]);
});

// Ruta de prueba (sin autenticación)
Route::get('/test', function () {
    return response()->json([
        'message' => 'API funcionando correctamente',
        'timestamp' => now()
    ]);
});

// Rutas públicas (sin autenticación)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas (requieren autenticación)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Usuario autenticado
    Route::get('/user', [AuthController::class, 'user']);

    // Rutas de Favoritos
    Route::prefix('favorites')->group(function () {
        Route::get('/', [FavoriteController::class, 'index']);
        Route::post('/', [FavoriteController::class, 'store']);
        Route::delete('/{id}', [FavoriteController::class, 'destroy']);
        Route::get('/check/{mediaType}/{tmdbId}', [FavoriteController::class, 'check']);
        Route::delete('/{mediaType}/{tmdbId}', [FavoriteController::class, 'destroyByTmdb']);
    });

    // Rutas de Watchlist
    Route::prefix('watchlist')->group(function () {
        Route::get('/', [WatchlistController::class, 'index']);
        Route::post('/', [WatchlistController::class, 'store']);
        Route::put('/{id}', [WatchlistController::class, 'update']);
        Route::delete('/{id}', [WatchlistController::class, 'destroy']);
        Route::get('/check/{mediaType}/{tmdbId}', [WatchlistController::class, 'check']);
    });

    // Rutas del Historial de Búsquedas
    Route::prefix('search')->group(function () {
        Route::post('/history', [SearchController::class, 'store']);
        Route::get('/history', [SearchController::class, 'index']);
        Route::delete('/history', [SearchController::class, 'clear']);
        Route::delete('/history/{id}', [SearchController::class, 'destroy']);
    });

    // Rutas de Perfil de Usuario
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::put('/profile/password', [AuthController::class, 'updatePassword']);
    Route::delete('/profile', [AuthController::class, 'deleteAccount']);
});

// Rutas de Películas (públicas)
Route::prefix('movies')->group(function () {
    Route::get('/popular', [App\Http\Controllers\Api\MovieController::class, 'popular']);
    Route::get('/top-rated', [App\Http\Controllers\Api\MovieController::class, 'topRated']);
    Route::get('/upcoming', [App\Http\Controllers\Api\MovieController::class, 'upcoming']);
    Route::get('/search', [App\Http\Controllers\Api\MovieController::class, 'search']);
    Route::get('/{id}', [App\Http\Controllers\Api\MovieController::class, 'show']);
    Route::get('/{id}/similar', [App\Http\Controllers\Api\MovieController::class, 'similar']);
});

// Rutas de Series (públicas)
Route::prefix('tv')->group(function () {
    Route::get('/popular', [App\Http\Controllers\Api\TVController::class, 'popular']);
    Route::get('/top-rated', [App\Http\Controllers\Api\TVController::class, 'topRated']);
    Route::get('/on-the-air', [App\Http\Controllers\Api\TVController::class, 'onTheAir']);
    Route::get('/search', [App\Http\Controllers\Api\TVController::class, 'search']);
    Route::get('/{id}', [App\Http\Controllers\Api\TVController::class, 'show']);
    Route::get('/{id}/similar', [App\Http\Controllers\Api\TVController::class, 'similar']);
});
