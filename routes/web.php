<?php

use App\Http\Controllers\ProfileController;
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

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/productos', function () {
    return Inertia::render('Productos');
})->middleware(['auth', 'verified'])->name('productos');

Route::get('/entrada', function () {
    return Inertia::render('Entrada');
})->middleware(['auth', 'verified'])->name('entrada');

Route::get('/salida', function () {
    return Inertia::render('Salida');
})->middleware(['auth', 'verified'])->name('salida');

Route::get('/area', function () {
    return Inertia::render('Area');
})->middleware(['auth', 'verified'])->name('area');

Route::get('/personal', function () {
    return Inertia::render('Personal');
})->middleware(['auth', 'verified'])->name('personal');

Route::get('/partidapresupuestal', function () {
    return Inertia::render('PartidaPresupuestal');
})->middleware(['auth', 'verified'])->name('partidapresupuestal');

Route::get('/usuarios', function () {
    return Inertia::render('Usuarios');
})->middleware(['auth', 'verified'])->name('usuarios');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
