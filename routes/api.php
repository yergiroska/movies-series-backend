<?php
use App\Http\Controllers\Api\AuthController;
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
});
