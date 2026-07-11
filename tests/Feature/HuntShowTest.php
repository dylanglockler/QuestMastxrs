<?php

namespace Tests\Feature;

use App\Models\Clue;
use App\Models\Hunt;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HuntShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_hunt_page_renders_clues_and_hints(): void
    {
        $hunt = Hunt::factory()->create();
        $clue = Clue::factory()->for($hunt)->create([
            'order' => 1,
            'riddle_text' => 'Where the shoes never stop but never move.',
        ]);

        $response = $this->get(route('hunts.show', $hunt));

        $response->assertOk();
        $response->assertSee($hunt->title);
        $response->assertSee('Where the shoes never stop but never move.');
        foreach ($clue->hints as $hint) {
            $response->assertSee($hint->text);
        }
    }
}
