<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tmdb_id',
        'media_type',
        'title',
        'poster_path',
        'rating',
        'review',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    // Relación con usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
