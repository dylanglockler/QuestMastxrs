<?php

namespace Tests\Feature;

use App\Models\Hunt;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_lists_active_hunts(): void
    {
        $active = Hunt::factory()->create(['title' => 'The Riverbend Ramble']);
        $draft = Hunt::factory()->draft()->create(['title' => 'Unpublished Hunt']);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('The Riverbend Ramble');
        $response->assertDontSee('Unpublished Hunt');
    }
}
