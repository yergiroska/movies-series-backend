<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Watchlist extends Model
{
    use HasFactory;

    //campos que se pueden asignar en masa
    protected $fillable = [
        'user_id',
        'tmdb_id',
        'media_type',
        'title',
        'poster_path',
        'status',
        'user_rating',
        'notes',
    ];

    protected $casts = [
        'tmdb_id' => 'integer',
        'user_rating' => 'decimal:1',
    ];

    // Constantes para estados
   /* const STATUS_WATCHING = 'watching';
    const STATUS_COMPLETED = 'completed';
    const STATUS_PLAN_TO_WATCH = 'plan_to_watch';
*/
    // Relación con User (un item de watchlist pertenece a un usuario)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

/*
    // Scopes por tipo de media
    public function scopeOfType($query, $type)
    {
        return $query->where('media_type', $type);
    }

    public function scopeMovies($query)
    {
        return $query->where('media_type', 'movie');
    }

    public function scopeTvShows($query)
    {
        return $query->where('media_type', 'tv');
    }

    // Scopes por estado
    public function scopeWatching($query)
    {
        return $query->where('status', self::STATUS_WATCHING);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopePlanToWatch($query)
    {
        return $query->where('status', self::STATUS_PLAN_TO_WATCH);
    }

    // Scope para items con rating
    public function scopeRated($query)
    {
        return $query->whereNotNull('user_rating');
    }

    // Scope para ordenar por fecha de actualización
    public function scopeRecentlyUpdated($query)
    {
        return $query->orderBy('updated_at', 'desc');
    }

    // Verificar si está en watchlist
    public static function isInWatchlist($userId, $tmdbId, $mediaType)
    {
        return self::where('user_id', $userId)
            ->where('tmdb_id', $tmdbId)
            ->where('media_type', $mediaType)
            ->exists();
    }

    // Agregar a watchlist
    public static function addToList($userId, $mediaData)
    {
        return self::updateOrCreate(
            [
                'user_id' => $userId,
                'tmdb_id' => $mediaData['tmdb_id'],
                'media_type' => $mediaData['media_type'],
            ],
            array_merge($mediaData, [
                'status' => $mediaData['status'] ?? self::STATUS_PLAN_TO_WATCH
            ])
        );
    }

    // Actualizar estado
    public function updateStatus($status)
    {
        $this->update(['status' => $status]);
        return $this;
    }

    // Actualizar rating
    public function rate($rating)
    {
        $this->update(['user_rating' => $rating]);
        return $this;
    }

    // Agregar o actualizar notas
    public function addNotes($notes)
    {
        $this->update(['notes' => $notes]);
        return $this;
    }
*/
}
