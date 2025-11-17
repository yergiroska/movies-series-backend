<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Watchlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WatchlistController extends Controller
{
    /**
     * Listar watchlist del usuario autenticado
     */
    public function index(Request $request)
    {
        $query = Auth::user()->watchlist()
            ->orderBy('created_at', 'desc');

        // Filtrar por estado si se proporciona
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $watchlist = $query->get();

        return response()->json([
            'watchlist' => $watchlist,
            'total' => $watchlist->count()
        ]);
    }

    /**
     * Agregar a watchlist
     */
    public function store(Request $request)
    {
        $request->validate([
            'tmdb_id' => 'required|integer',
            'media_type' => 'required|in:movie,tv',
            'title' => 'required|string',
            'poster_path' => 'nullable|string',
            'status' => 'nullable|in:watching,completed,plan_to_watch',
            'user_rating' => 'nullable|numeric|between:0,10',
            'notes' => 'nullable|string',
        ]);

        // Verificar si ya existe
        $exists = Watchlist::where('user_id', Auth::id())
            ->where('tmdb_id', $request->tmdb_id)
            ->where('media_type', $request->media_type)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Ya existe en la lista de seguimiento'
            ], 409);
        }

        $watchlist = Watchlist::create([
            'user_id' => Auth::id(),
            'tmdb_id' => $request->tmdb_id,
            'media_type' => $request->media_type,
            'title' => $request->title,
            'poster_path' => $request->poster_path,
            'status' => $request->status ?? 'plan_to_watch',
            'user_rating' => $request->user_rating,
            'notes' => $request->notes,
        ]);

        return response()->json([
            'message' => 'Agregado a lista de seguimiento exitosamente',
            'watchlist' => $watchlist
        ], 201);
    }

    /**
     * Actualizar watchlist
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'nullable|in:watching,completed,plan_to_watch',
            'user_rating' => 'nullable|numeric|between:0,10',
            'notes' => 'nullable|string',
        ]);

        $watchlist = Watchlist::where('user_id', Auth::id())
            ->where('id', $id)
            ->first();

        if (!$watchlist) {
            return response()->json([
                'message' => 'Item no encontrado en watchlist'
            ], 404);
        }

        $watchlist->update($request->only(['status', 'user_rating', 'notes']));

        return response()->json([
            'message' => 'Actualizado exitosamente',
            'watchlist' => $watchlist
        ]);
    }

    /**
     * Eliminar de watchlist
     */
    public function destroy($id)
    {
        $watchlist = Watchlist::where('user_id', Auth::id())
            ->where('id', $id)
            ->first();

        if (!$watchlist) {
            return response()->json([
                'message' => 'Item no encontrado en watchlist'
            ], 404);
        }

        $watchlist->delete();

        return response()->json([
            'message' => 'Eliminado de watchlist exitosamente'
        ]);
    }

    /**
     * Verificar si un item está en watchlist
     */
    public function check($mediaType, $tmdbId)
    {
        $item = Watchlist::where('user_id', Auth::id())
            ->where('tmdb_id', $tmdbId)
            ->where('media_type', $mediaType)
            ->first();

        return response()->json([
            'in_watchlist' => $item !== null,
            'watchlist' => $item
        ]);
    }
}
