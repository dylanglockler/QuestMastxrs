<?php

namespace App\Http\Controllers;

use App\Models\Hunt;
use Illuminate\View\View;

class HuntController extends Controller
{
    public function show(Hunt $hunt): View
    {
        $hunt->load([
            'clues.hints',
            'clues.messages' => fn ($query) => $query->visible()->latest(),
            'photos' => fn ($query) => $query->visible()->latest(),
        ]);

        return view('hunts.show', ['hunt' => $hunt]);
    }
}
