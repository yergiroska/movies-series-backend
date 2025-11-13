<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relación con Favoritos Un usuario puede tener varios favoritos
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Relación con Watchlist un usuario puede tener varios items en watchlist
     */
    public function watchlist()
    {
        return $this->hasMany(Watchlist::class);
    }

    /**
     * Relación con Historial de búsquedas de un usuario.
     */
    public function searchHistory()
    {
        return $this->hasMany(SearchHistory::class);
    }

    /**
     * Obtener favoritos por tipo de media
     */
    public function getFavoritesByType($type)
    {
        return $this->favorites()->ofType($type)->recent()->get();
    }

    /**
     * Obtener watchlist por estado
     */
    public function getWatchlistByStatus($status)
    {
        return $this->watchlist()->where('status', $status)->recentlyUpdated()->get();
    }

    /**
     * Obtener búsquedas recientes
     */
    public function getRecentSearches($limit = 10)
    {
        return SearchHistory::getRecentUnique($this->id, $limit);
    }
}
