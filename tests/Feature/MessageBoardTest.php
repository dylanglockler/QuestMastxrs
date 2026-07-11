<?php

namespace Tests\Feature;

use App\Models\Clue;
use App\Models\Hunt;
use App\Models\Message;
use App\Models\User;
use App\Notifications\NewSubmissionNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class MessageBoardTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_quester_can_post_a_message_on_a_clue(): void
    {
        Notification::fake();

        Role::firstOrCreate(['name' => 'host', 'guard_name' => 'web']);
        $host = User::factory()->create();
        $host->assignRole('host');

        $hunt = Hunt::factory()->create();
        $clue = Clue::factory()->for($hunt)->create();

        $response = $this->post(route('clues.messages.store', ['hunt' => $hunt, 'clue' => $clue]), [
            'nickname' => 'TrailBlazer',
            'body' => 'Found it under the bench!',
        ]);

        $response->assertRedirect(route('hunts.show', $hunt).'#clue-'.$clue->id);
        $this->assertDatabaseHas('messages', [
            'clue_id' => $clue->id,
            'nickname' => 'TrailBlazer',
            'body' => 'Found it under the bench!',
        ]);

        Notification::assertSentTo($host, NewSubmissionNotification::class);
    }

    public function test_hidden_messages_are_not_shown_publicly(): void
    {
        $hunt = Hunt::factory()->create();
        $clue = Clue::factory()->for($hunt)->create();
        $host = User::factory()->create();

        $visible = Message::factory()->for($clue)->create(['nickname' => 'VisibleQuester']);
        $hidden = Message::create([
            'clue_id' => $clue->id,
            'nickname' => 'HiddenQuester',
            'body' => 'This should not show.',
            'hidden_at' => now(),
            'hidden_by' => $host->id,
        ]);

        $response = $this->get(route('hunts.show', $hunt));

        $response->assertSee('VisibleQuester');
        $response->assertDontSee('HiddenQuester');
    }
}
