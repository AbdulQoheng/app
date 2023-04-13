<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\TodolistController;
use Illuminate\Support\Facades\Route;

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
    return redirect()->route('login');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'LoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'loginPost'])->name('login.post');

    Route::get('register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('register', [AuthController::class, 'registerPost'])->name('register.post');

});

Route::prefix('panel')->middleware('auth')->group(function () {
    Route::post('password', [AuthController::class, 'password'])->name('password.change');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    });

    Route::prefix('note')->group(function () {
        Route::get('/', [NoteController::class, 'index'])->name('note');
        Route::get('/{note:id_note}', [NoteController::class, 'show'])->name('note.show');
        Route::post('/', [NoteController::class, 'save'])->name('note.save');
        Route::delete('/{note:id_note}', [NoteController::class, 'delete'])->name('note.delete');
    });
    Route::prefix('todolist')->group(function () {
        Route::get('/', [TodolistController::class, 'index'])->name('todolist');
        Route::get('/{todolist:id_todolist}', [TodolistController::class, 'show'])->name('todolist.show');
        Route::post('/', [TodolistController::class, 'save'])->name('todolist.save');
        Route::delete('/{todolist:id_todolist}', [TodolistController::class, 'delete'])->name('todolist.delete');
    });
});
