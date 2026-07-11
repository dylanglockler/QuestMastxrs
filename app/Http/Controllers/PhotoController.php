<?php

namespace App\Http\Controllers;

use App\Models\Hunt;
use App\Models\User;
use App\Notifications\NewSubmissionNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class PhotoController extends Controller
{
    public function store(Request $request, Hunt $hunt): RedirectResponse
    {
        $data = $request->validate([
            'nickname' => ['required', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:255'],
            'image' => ['required', 'image', 'max:8192'],
        ]);

        $path = $request->file('image')->store('photos', 'public');

        $photo = $hunt->photos()->create([
            'nickname' => $data['nickname'],
            'caption' => $data['caption'] ?? null,
            'path' => $path,
        ]);

        Notification::send(User::role('host')->get(), new NewSubmissionNotification($photo));

        return redirect()
            ->to(route('hunts.show', $hunt).'#photos')
            ->cookie('quester_nickname', $data['nickname'], 60 * 24 * 365)
            ->with('status', 'Photo posted!');
    }
}
