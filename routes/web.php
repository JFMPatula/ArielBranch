<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MoodEntryController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Dashboard
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Authenticated Routes for Inertia Pages
Route::middleware(['auth', 'verified'])->group(function () {
    // Mood Tracking Routes
    Route::get('/mood-entries', function () {
        return Inertia::render('MoodEntries');
    })->name('mood-entries');
    
    Route::post('/mood-entry', [MoodEntryController::class, 'store'])->name('mood-entry.store');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// API Routes for mood entries data (JSON responses)
Route::middleware('auth:sanctum')->group(function () {
    // Fetch mood entries (JSON response for the frontend)
    Route::get('/api/mood-entries', [MoodEntryController::class, 'index']);
});

// Include Auth Routes
require __DIR__ . '/auth.php';
