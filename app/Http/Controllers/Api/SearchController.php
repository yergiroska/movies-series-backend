<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SearchHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    /**
     * Guardar búsqueda en historial
     */
    public function store(Request $request)
    {
        $request->validate([
            'search_query' => 'required|string|min:1|max:255'
        ]);

        // Evitar duplicados recientes (últimas 10 búsquedas)
        $recentSearch = SearchHistory::where('user_id', Auth::id())
            ->where('search_query', $request->search_query)
            ->latest('searched_at')
            ->first();

        // Si ya existe en las últimas búsquedas, no crear duplicado
        if ($recentSearch) {
            $recentCount = SearchHistory::where('user_id', Auth::id())
                ->where('searched_at', '>', $recentSearch->searched_at)
                ->count();

            if ($recentCount < 10) {
                return response()->json([
                    'message' => 'Búsqueda ya registrada recientemente'
                ]);
            }
        }

        $search = SearchHistory::create([
            'user_id' => Auth::id(),
            'search_query' => $request->search_query,
        ]);

        return response()->json([
            'message' => 'Búsqueda guardada en historial',
            'search' => $search
        ], 201);
    }

    /**
     * Listar historial de búsquedas
     */
    public function index(Request $request)
    {
        $limit = $request->query('limit', 20);

        $history = SearchHistory::where('user_id', Auth::id())
            ->orderBy('searched_at', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'history' => $history,
            'total' => $history->count()
        ]);
    }

    /**
     * Limpiar todo el historial
     */
    public function clear()
    {
        $deleted = SearchHistory::where('user_id', Auth::id())->delete();

        return response()->json([
            'message' => 'Historial limpiado exitosamente',
            'deleted_count' => $deleted
        ]);
    }

    /**
     * Eliminar búsqueda individual
     */
    public function destroy($id)
    {
        $search = SearchHistory::where('user_id', Auth::id())
            ->where('id', $id)
            ->first();

        if (!$search) {
            return response()->json([
                'message' => 'Búsqueda no encontrada'
            ], 404);
        }

        $search->delete();

        return response()->json([
            'message' => 'Búsqueda eliminada del historial'
        ]);
    }
}
