<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\UserController;

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

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rutas de Encuestas
    Route::patch('/surveys/{survey}/toggle-status', [SurveyController::class, 'toggleStatus'])->name('surveys.toggle-status');
    Route::resource('surveys', SurveyController::class);
    
    // Ruta de Estadísticas
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics.index');

    // Rutas exclusivas para Administradores
    Route::middleware(['role:admin'])->group(function () {
        // Ruta de Bitácora
        Route::get('/activity-logs/export', [ActivityLogController::class, 'export'])->name('activity-logs.export');
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
        
        // Rutas de Usuarios
        Route::resource('users', UserController::class);
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Rutas Públicas para responder encuestas
Route::get('/s/{id}', [SurveyController::class, 'showPublic'])->name('surveys.public');
Route::post('/s/{id}', [SurveyController::class, 'storeAnswer'])->name('surveys.store-answer');
Route::get('/s/{id}/thank-you', [SurveyController::class, 'thankYou'])->name('surveys.thank-you');
