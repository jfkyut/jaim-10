<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\RedeemController;
use App\Http\Controllers\CreatorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\SubscriptionController;

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


Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
        ->name('dashboard')
        ->middleware('admin');

    Route::get('/edit-profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('music/upload', [MusicController::class, 'store'])->name('music.store');
    Route::post('/music/{music}/stream', [MusicController::class, 'stream'])->name('music.stream');

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

    Route::get('/creators', [CreatorController::class, 'index'])->name('creator.index');

    Route::get('/discover-people', [PeopleController::class, 'index'])->name('people.index');
    Route::get('/people/{user}/profile', [PeopleController::class, 'show'])->name('people.show');

    Route::post('/follow/{user}', [FollowController::class, 'follow'])->name('user.follow');
    Route::post('/unfollow/{user}', [FollowController::class, 'unfollow'])->name('user.unfollow');

    // for favorites
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorite.index');
    Route::post('/favorites/{music}/store', [FavoriteController::class, 'store'])->name('favorite.store');
    Route::delete('/favorites/{music}/destroy', [FavoriteController::class, 'destroy'])->name('favorite.destroy');

    Route::get('/redeems-pending', [RedeemController::class, 'pending'])->name('redeem.pending');
    Route::get('/redeems-approved', [RedeemController::class, 'approved'])->name('redeem.approved');
    Route::get('/redeems-rejected', [RedeemController::class, 'rejected'])->name('redeem.rejected');
    Route::post('/redeem/store', [RedeemController::class, 'store'])->name('redeem.store');
    Route::post('/redeem/{redeem}/approve', [RedeemController::class, 'approve'])->name('redeem.approve');
    Route::post('/redeem/{redeem}/reject', [RedeemController::class, 'reject'])->name('redeem.reject');

    Route::get('subscription-history', [SubscriptionController::class, 'index'])->name('subscription.index');
    Route::get('subscriptions', [SubscriptionController::class, 'create'])->name('subscription.create');
    Route::post('subscriptions/{plan}/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscription.subscribe');
    Route::get('subscriptions/success', [SubscriptionController::class, 'success'])->name('subscription.success');
});

require __DIR__.'/auth.php';
