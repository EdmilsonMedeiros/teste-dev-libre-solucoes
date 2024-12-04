<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function(){
    return redirect()->route('login');
});


Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('contact')->group(function () {
        Route::get('/create', [ContactController::class, 'create'])->name('contact.create');
        Route::post('/store', [ContactController::class, 'store'])->name('contact.store');
        Route::put('/update/{id}', [ContactController::class, 'update'])->name('contact.update');
        Route::get('/edit/{id}', [ContactController::class, 'edit'])->name('contact.edit');
        Route::delete('/destroy/{id}', [ContactController::class, 'destroy'])->name('contact.destroy');
    });
    
});