
<?php

use App\Http\Controllers\EventoController;
use App\Http\Controllers\PerguntaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

// Rotas protegidas por autenticacao
Route::middleware('auth')->group(function () {

    Route::get('/eventos/create', [EventoController::class, 'create'])
        ->name('eventos.create');

    Route::post('/eventos', [EventoController::class, 'store'])
        ->name('eventos.store');

    // Sprint 05 - Exclusao de perguntas
    Route::delete('/perguntas/{pergunta}', [EventoController::class, 'destroyPergunta'])
        ->name('perguntas.destroy');

    // Sprint 06 - Votar ou retirar voto
    Route::post('/perguntas/{pergunta}/votar', [PerguntaController::class, 'votar'])
        ->name('perguntas.votar');

});

// Rotas publicas
Route::get('/', [EventoController::class, 'index'])
    ->name('eventos.index');

Route::get('/eventos/{id}', [EventoController::class, 'show'])
    ->name('eventos.show');

// Envio de perguntas exige login
Route::post('/eventos/{id}/perguntas', [EventoController::class, 'storePergunta'])
    ->middleware('auth')
    ->name('eventos.perguntas.store');

// Registro
Route::get('/register', [RegisterController::class, 'create'])
    ->name('register.create');

Route::post('/register', [RegisterController::class, 'store'])
    ->name('register.store');

// Login
Route::get('/login', [LoginController::class, 'create'])
    ->name('login.create');

Route::post('/login', [LoginController::class, 'store'])
    ->name('login.store');

// Logout
Route::post('/logout', [LoginController::class, 'logout'])
    ->name('auth.logout');
