<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    // Deshabilitar timestamps automáticos ya que solo usas added_at
    public $timestamps = false;

    // Atributos que se pueden asignar en masa
    protected $fillable = [
        'user_id',
        'tmdb_id',
        'media_type',
        'title',
        'poster_path',
        'overview',
    ];

    protected $casts = [
        'tmdb_id' => 'integer',
        'added_at' => 'datetime',
    ];

    // Relación con User (un favorito pertenece a un usuario)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
/*
    // Scope para filtrar por tipo de media
    public function scopeOfType($query, $type)
    {
        return $query->where('media_type', $type);
    }

    // Scope para películas
    public function scopeMovies($query)
    {
        return $query->where('media_type', 'movie');
    }

    // Scope para series
    public function scopeTvShows($query)
    {
        return $query->where('media_type', 'tv');
    }

    // Scope para ordenar por más recientes
    public function scopeRecent($query)
    {
        return $query->orderBy('added_at', 'desc');
    }

    // Verificar si un item ya está en favoritos
    public static function isFavorite($userId, $tmdbId, $mediaType)
    {
        return self::where('user_id', $userId)
            ->where('tmdb_id', $tmdbId)
            ->where('media_type', $mediaType)
            ->exists();
    }

    // Toggle favorito
    public static function toggle($userId, $mediaData)
    {
        $favorite = self::where('user_id', $userId)
            ->where('tmdb_id', $mediaData['tmdb_id'])
            ->where('media_type', $mediaData['media_type'])
            ->first();

        if ($favorite) {
            $favorite->delete();
            return ['action' => 'removed', 'favorite' => null];
        }

        $favorite = self::create(array_merge($mediaData, ['user_id' => $userId]));
        return ['action' => 'added', 'favorite' => $favorite];
    }
*/
}
