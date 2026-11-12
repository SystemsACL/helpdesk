<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\PublicTicketController;
use App\Http\Middleware\ForcePasswordChange;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

// ---- Público ----
Route::get('/', [PublicTicketController::class, 'create'])->name('solicitud.create');
Route::post('/solicitud', [PublicTicketController::class, 'store'])->middleware('throttle:5,1')->name('solicitud.store');
Route::get('/gracias', [PublicTicketController::class, 'thanks'])->name('gracias');

// ---- Acceso ----
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1')->name('login.attempt');

// ---- Panel (admin y agente) ----
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/cuenta', [AuthController::class, 'account'])->name('password.edit');
    Route::post('/cuenta', [AuthController::class, 'updatePassword'])->name('password.update');

    Route::middleware(ForcePasswordChange::class)->group(function () {
        Route::get('/panel', [PanelController::class, 'dashboard'])->name('panel');
        Route::get('/tickets', [PanelController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/{ticket}', [PanelController::class, 'show'])->name('tickets.show');
        Route::post('/tickets/{ticket}/status', [PanelController::class, 'status'])->name('tickets.status');
        Route::post('/tickets/{ticket}/assign', [PanelController::class, 'assign'])->name('tickets.assign');
        Route::post('/tickets/{ticket}/comment', [PanelController::class, 'comment'])->name('tickets.comment');
                Route::get('/ajustes', [SettingsController::class, 'edit'])->name('settings.edit');
        Route::post('/ajustes', [SettingsController::class, 'update'])->name('settings.update');
                Route::get('/usuarios', [UsersController::class, 'index'])->name('users.index');
        Route::get('/usuarios/nuevo', [UsersController::class, 'create'])->name('users.create');
        Route::post('/usuarios', [UsersController::class, 'store'])->name('users.store');
        Route::get('/usuarios/{user}/editar', [UsersController::class, 'edit'])->name('users.edit');
        Route::post('/usuarios/{user}', [UsersController::class, 'update'])->name('users.update');
    });
});
