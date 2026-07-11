<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\HuntController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PhotoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/hunts/{hunt:slug}', [HuntController::class, 'show'])->name('hunts.show');
Route::post('/hunts/{hunt:slug}/clues/{clue}/messages', [MessageController::class, 'store'])->name('clues.messages.store');
Route::post('/hunts/{hunt:slug}/photos', [PhotoController::class, 'store'])->name('hunts.photos.store');
