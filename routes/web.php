<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\FacturationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\RendezVousController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\PoneyController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// ========================
// 🔐 AUTHENTIFICATION
// ========================
// Affichage du formulaire de connexion
Route::get('/', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
// Soumission du formulaire de connexion
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
// Déconnexion
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Affichage du formulaire d'inscription
Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup')->middleware('guest');

// Soumission du formulaire d'inscription
Route::post('/signup', [AuthController::class, 'register'])->name('signup.process');


// ========================
// 🔒 ZONE PROTÉGÉE (AUTH)
// ========================
Route::middleware(['auth'])->group(function () {

    // 🏠 Tableau de bord
    Route::get('/dashboard', function () {
        return view('dashboard.welcome');
    })->name('dashboard.welcome');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');

    // ========================
    // 📁 GESTION DES CLIENTS
    // ========================

    Route::resource('clients', ClientController::class)->except(['destroy']);
    Route::delete('/clients/{client}', [ClientController::class, 'destroy'])
        ->name('clients.destroy')
        ->middleware('can:delete,client'); // Utilisation correcte
    Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
    Route::get('/clients/{id}/invoice', [ClientController::class, 'generateInvoice'])->name('clients.invoice');

    // ========================
    // 🐴 GESTION DES PONEYS
    // ========================
    Route::resource('poneys', PoneyController::class)->except(['destroy']);
    Route::delete('/poneys/{poney}', [PoneyController::class, 'destroy'])
        ->name('poneys.destroy')
        ->middleware('can:delete,poney');

    // ========================
    // 📆 GESTION DES RENDEZ-VOUS
    // ========================
    Route::resource('rendez-vous', RendezVousController::class);
    Route::post('/rendez-vous/{id}/confirm', [RendezVousController::class, 'confirm'])->name('rendez-vous.confirm');
    Route::patch('/rendez-vous/{id}/reset', [RendezVousController::class, 'reset'])->name('rendez-vous.reset');
    Route::put('/rendez-vous/{id}', [RendezVousController::class, 'update'])->name('rendez-vous.update');
    Route::get('/rendez-vous/{id}/edit', [RendezVousController::class, 'edit'])->name('rendez-vous.edit');
    Route::delete('/rendez-vous/{id}', [RendezVousController::class, 'destroy'])->name('rendez-vous.destroy');
    Route::post('/rendez-vous/assigner/{id}', [RendezVousController::class, 'assignerPoneys'])->name('rendez-vous.assigner');

    // ========================
    // 💰 GESTION DE LA FACTURATION
    // ========================
    Route::resource('facturation', FacturationController::class);

    // ========================
    // ⚙️ PARAMÈTRES ET AUTRES
    // ========================
    Route::get('/rapports', [RapportController::class, 'index'])
        ->name('rapports.index')
        ->middleware(AdminMiddleware::class);
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('/support', [SupportController::class, 'index'])->name('support.index');

}
);
