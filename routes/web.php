<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');

Route::get('/bonjour', [PageController::class, 'bonjour'])->name('bonjour');
Route::get('/bonjour-court', [PageController::class, 'bonjourCourt'])->name('bonjour.court');
Route::get('/bienvenue', [PageController::class, 'bienvenue'])->name('bienvenue');
Route::get('/version', [PageController::class, 'version'])->name('version');
Route::get('/heure', [PageController::class, 'heure'])->name('heure');

Route::prefix('posts')
    ->name('posts.')
    ->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('index');
        Route::get('/{slug}', [PostController::class, 'show'])
            ->where('slug', '[a-z0-9-]+')
            ->name('show');
    });

Route::prefix('pages')
    ->name('pages.')
    ->group(function () {
        Route::get('/{page}', [PageController::class, 'show'])
            ->whereNumber('page')
            ->name('show');
    });
