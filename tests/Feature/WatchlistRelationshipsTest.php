<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Watchlist;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class WatchlistRelationshipsTest extends TestCase
{
    use DatabaseTransactions;

    public function test_usuario_puede_tener_multiples_items_en_watchlist(): void
    {
        // 1. ARRANGE - Preparar el escenario
        $user = User::factory()->create();

        // 2. ACT - Crear 3 items en watchlist para este usuario
        Watchlist::factory()->count(3)->create([
            'user_id' => $user->id,
            'status' => 'watching',
            'user_rating' => 4.0,
            'notes' => 'Test note'
        ]);

        // 3. ASSERT - Verificar el resultado
        $this->assertCount(3, $user->watchlist);

        // Verificar que los datos se guardaron correctamente
        $firstWatchlistItem = $user->watchlist->first();
        $this->assertEquals('watching', $firstWatchlistItem->status);
        $this->assertEquals(4.0, $firstWatchlistItem->user_rating);
        $this->assertEquals('Test note', $firstWatchlistItem->notes);
    }
}
