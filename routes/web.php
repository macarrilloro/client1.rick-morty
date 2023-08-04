<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\LocationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/character',[CharacterController::class,'view'])->name('character.view');
    Route::get('/location',[LocationController::class,'view'])->name('location.view');
    Route::get('/episode',[EpisodeController::class,'view'])->name('episode.view');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Route::get('characters',[CharacterController::class,'store'])->middleware('auth');
Route::resource('characters', CharacterController::class)->names('characters')->middleware('auth');
Route::resource('locations', LocationController::class)->names('locations')->middleware('auth');
Route::resource('episodes', EpisodeController::class)->names('episodes')->middleware('auth');