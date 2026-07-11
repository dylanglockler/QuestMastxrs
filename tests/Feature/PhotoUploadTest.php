<?php

namespace Tests\Feature;

use App\Models\Hunt;
use App\Models\User;
use App\Notifications\NewSubmissionNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PhotoUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_quester_can_upload_a_celebratory_photo(): void
    {
        Storage::fake('public');
        Notification::fake();

        Role::firstOrCreate(['name' => 'host', 'guard_name' => 'web']);
        $host = User::factory()->create();
        $host->assignRole('host');

        $hunt = Hunt::factory()->create();

        $response = $this->post(route('hunts.photos.store', $hunt), [
            'nickname' => 'VictoryLap',
            'caption' => 'We made it!',
            'image' => UploadedFile::fake()->image('finish-line.jpg'),
        ]);

        $response->assertRedirect(route('hunts.show', $hunt).'#photos');
        $this->assertDatabaseHas('photos', [
            'hunt_id' => $hunt->id,
            'nickname' => 'VictoryLap',
            'caption' => 'We made it!',
        ]);

        $photo = $hunt->photos()->first();
        Storage::disk('public')->assertExists($photo->path);

        Notification::assertSentTo($host, NewSubmissionNotification::class);
    }

    public function test_photo_upload_requires_an_image(): void
    {
        $hunt = Hunt::factory()->create();

        $response = $this->post(route('hunts.photos.store', $hunt), [
            'nickname' => 'NoPhoto',
        ]);

        $response->assertSessionHasErrors('image');
        $this->assertDatabaseCount('photos', 0);
    }
}
