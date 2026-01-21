<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Watchlist;
use Tests\TestCase;

class WatchlistTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_puede_guardar_un_item_en_watchlist(): void
    {
        $user = User::find(1);

        $watchlist = new Watchlist();
        $watchlist->user_id = $user->id;
        $watchlist->tmdb_id = 550;
        $watchlist->media_type = 'movie';
        $watchlist->title = 'Fight Club';
        $watchlist->notes = 'Película que quiero ver este fin de semana';

        $watchlist->save(); // ¡ESTO ES LO QUE FALTABA!

        // Assert - Verificar resultados
        $this->assertDatabaseHas('watchlists', [
            'user_id' => $user->id,
            'tmdb_id' => 550,
            'media_type' => 'movie',
            'title' => 'Fight Club',
        ]);

        $this->assertEquals(550, $watchlist->tmdb_id);
        $this->assertEquals('movie', $watchlist->media_type);
        $this->assertEquals('Fight Club', $watchlist->title);
        $this->assertEquals(9.5, $watchlist->user_rating);
    }
}
