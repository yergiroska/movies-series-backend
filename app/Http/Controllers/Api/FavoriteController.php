<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Listar favoritos del usuario autenticado
     */
    public function index()
    {
        $favorites = Auth::user()->favorites()
            ->orderBy('added_at', 'desc')
            ->get();

        return response()->json([
            'favorites' => $favorites,
            'total' => $favorites->count()
        ]);
    }

    /**
     * Agregar a favoritos
     */
    public function store(Request $request)
    {
        $request->validate([
            'tmdb_id' => 'required|integer',
            'media_type' => 'required|in:movie,tv',
            'title' => 'required|string',
            'poster_path' => 'nullable|string',
            'overview' => 'nullable|string',
        ]);

        // Verificar si ya existe
        $exists = Favorite::where('user_id', Auth::id())
            ->where('tmdb_id', $request->tmdb_id)
            ->where('media_type', $request->media_type)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Ya existe en favoritos'
            ], 409); // Conflict
        }

        $favorite = Favorite::create([
            'user_id' => Auth::id(),
            'tmdb_id' => $request->tmdb_id,
            'media_type' => $request->media_type,
            'title' => $request->title,
            'poster_path' => $request->poster_path,
            'overview' => $request->overview,
        ]);

        return response()->json([
            'message' => 'Agregado a favoritos exitosamente',
            'favorite' => $favorite
        ], 201);
    }

    /**
     * Eliminar de favoritos
     */
    public function destroy($id)
    {
        $favorite = Favorite::where('user_id', Auth::id())
            ->where('id', $id)
            ->first();

        if (!$favorite) {
            return response()->json([
                'message' => 'Favorito no encontrado'
            ], 404);
        }

        $favorite->delete();

        return response()->json([
            'message' => 'Eliminado de favoritos exitosamente'
        ]);
    }

    /**
     * Verificar si un item es favorito
     */
    public function check($mediaType, $tmdbId)
    {
        $isFavorite = Favorite::where('user_id', Auth::id())
            ->where('tmdb_id', $tmdbId)
            ->where('media_type', $mediaType)
            ->exists();

        return response()->json([
            'is_favorite' => $isFavorite
        ]);
    }

    /**
     * Eliminar por tmdb_id y media_type (alternativa)
     */
    public function destroyByTmdb($mediaType, $tmdbId)
    {
        $favorite = Favorite::where('user_id', Auth::id())
            ->where('tmdb_id', $tmdbId)
            ->where('media_type', $mediaType)
            ->first();

        if (!$favorite) {
            return response()->json([
                'message' => 'Favorito no encontrado'
            ], 404);
        }

        $favorite->delete();

        return response()->json([
            'message' => 'Eliminado de favoritos exitosamente'
        ]);
    }
}
