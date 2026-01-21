<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CineVerseReviewController extends Controller
{
    /**
     * Listar todas las reseñas del usuario
     */
    public function index()
    {
        $reviews = Auth::user()->reviews()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'reviews' => $reviews
        ]);
    }

    /**
     * Crear o actualizar reseña
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'tmdb_id' => 'required|integer',
            'title' => 'required|string',
            'rating' => 'nullable|integer|min:1|max:5',
            'review' => 'nullable|string|max:2000',
        ]);
        $media_type = $request->route('media_type');

        $review = Review::create([
            'user_id' => $validated['user_id'],
            'tmdb_id' => $validated['tmdb_id'],
            'media_type' => $media_type,
            'title' => $validated['title'],
            'poster_path' => $validated['poster_path'] ?? null,
            'rating' => $validated['rating'] ?? null,
            'review' => $validated['review'] ?? null,
        ]);

        return response()->json([
            'message' => 'Reseña guardada exitosamente',
            'review' => $review
        ]);
    }

    /**
     * Obtener reseña específica
     */
    public function show($mediaType, $tmdbId)
    {
        // Obtener TODAS las reviews de esta película/serie
        $reviews = Review::where('media_type', $mediaType)
            ->where('tmdb_id', $tmdbId)
            ->with('user:id,name') // Incluir info del usuario que hizo la review
            ->orderBy('created_at', 'desc') // Más recientes primero
            ->get();

        return response()->json([
            'reviews' => $reviews,
            'total' => $reviews->count()
        ]);
    }
}
