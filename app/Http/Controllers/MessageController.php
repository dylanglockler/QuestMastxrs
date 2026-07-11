<?php

namespace App\Http\Controllers;

use App\Models\Clue;
use App\Models\Hunt;
use App\Models\User;
use App\Notifications\NewSubmissionNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MessageController extends Controller
{
    public function store(Request $request, Hunt $hunt, Clue $clue): RedirectResponse
    {
        if ($clue->hunt_id !== $hunt->id) {
            throw new NotFoundHttpException;
        }

        $data = $request->validate([
            'nickname' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $message = $clue->messages()->create($data);

        Notification::send(User::role('host')->get(), new NewSubmissionNotification($message));

        return redirect()
            ->to(route('hunts.show', $hunt).'#clue-'.$clue->id)
            ->cookie('quester_nickname', $data['nickname'], 60 * 24 * 365)
            ->with('status', 'Message posted!');
    }
}
