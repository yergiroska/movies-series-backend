<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchHistory extends Model
{
    use HasFactory;

    // Deshabilitar timestamps automáticos ya que solo usas searched_at
    public $timestamps = false;

    // Nombre de la tabla en la base de datos
    protected $table = 'search_history';

    // Atributos que se pueden asignar en masa
    protected $fillable = [
        'user_id',
        'search_query',
    ];

    protected $casts = [
        'searched_at' => 'datetime',
    ];

    // Relación con User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    // Obtener búsquedas recientes del usuario
    public static function getRecent($userId, $limit = 10)
    {
        return self::where('user_id', $userId)
            ->orderBy('searched_at', 'desc')
            ->limit($limit)
            ->get();
    }

    // Obtener búsquedas únicas recientes (sin duplicados)
    public static function getRecentUnique($userId, $limit = 10)
    {
        return self::where('user_id', $userId)
            ->select('search_query')
            ->groupBy('search_query')
            ->orderByRaw('MAX(searched_at) desc')
            ->limit($limit)
            ->pluck('search_query');
    }

    // Obtener búsquedas más populares del usuario
    public static function getPopular($userId, $limit = 5)
    {
        return self::where('user_id', $userId)
            ->selectRaw('search_query, COUNT(*) as search_count')
            ->groupBy('search_query')
            ->orderBy('search_count', 'desc')
            ->limit($limit)
            ->pluck('search_query');
    }

    // Registrar una búsqueda
    public static function record($userId, $query)
    {
        return self::create([
            'user_id' => $userId,
            'search_query' => trim($query),
        ]);
    }

    // Limpiar historial antiguo (más de X días)
    public static function cleanOld($userId, $days = 30)
    {
        return self::where('user_id', $userId)
            ->where('searched_at', '<', now()->subDays($days))
            ->delete();
    }

    // Limpiar todo el historial del usuario
    public static function clearAll($userId)
    {
        return self::where('user_id', $userId)->delete();
    }

    // Eliminar búsqueda específica
    public static function deleteQuery($userId, $query)
    {
        return self::where('user_id', $userId)
            ->where('search_query', $query)
            ->delete();
    }
    */
}
