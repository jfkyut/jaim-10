<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlaylistController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('welcome');

Route::get('music/explore', [MusicController::class, 'index'])->name('music.index');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('music/upload', [MusicController::class, 'store'])->name('music.store');

    // album routes
    Route::get('creation/my-albums', [App\Http\Controllers\CreationController::class, 'myAlbums'])->name('creation.myAlbums');
    
    Route::post('album/store', [AlbumController::class, 'store'])->name('album.store');
    Route::put('album/{album}/update', [AlbumController::class, 'update'])->name('album.update');
    Route::delete('album/{album}/delete', [AlbumController::class, 'destroy'])->name('album.destroy');
    Route::get('albums', [AlbumController::class, 'index'])->name('album.index');
    Route::get('album/{album}', [AlbumController::class, 'show'])->name('album.show');

    // playlist routes
    Route::post('playlist/store', [PlaylistController::class, 'store'])->name('playlist.store');
    Route::get('playlist/{playlist}', [PlaylistController::class, 'show'])->name('playlist.show');
    Route::post('/playlists/{playlist}/add-music', [PlaylistController::class, 'addMusic'])->name('playlist.add-music');
});

require __DIR__.'/auth.php';
