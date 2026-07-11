<?php

namespace App\Http\Controllers;

use App\Models\Hunt;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $hunts = Hunt::active()->latest('published_at')->get();

        return view('home', ['hunts' => $hunts]);
    }
}
