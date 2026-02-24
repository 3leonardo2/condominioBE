<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\AdminController;

// Rutas Públicas
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/activate-account', [InvitationController::class, 'activate']); // ← pública

// Rutas Protegidas
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) { return $request->user(); });
    Route::get('/messages', [ChatController::class, 'fetchMessages']);
    Route::post('/messages', [ChatController::class, 'sendMessage']);

    // Rutas solo para Admins
    Route::middleware('admin')->group(function () {
        Route::post('/invite', [InvitationController::class, 'invite']);
        Route::post('/invite/resend', [InvitationController::class, 'resend']);

        Route::get('/admin/residentes', [AdminController::class, 'listarResidentes']);
        Route::get('/admin/departamentos', [AdminController::class, 'listarDepartamentos']);
        Route::get('/admin/roles', [AdminController::class, 'listarRoles']);
        Route::patch('/admin/residentes/{id}/desactivar', [AdminController::class, 'desactivarResidente']);
        Route::patch('/admin/residentes/{id}/reactivar', [AdminController::class, 'reactivarResidente']);
    });
});

Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])
    ->middleware(['signed'])
    ->name('verification.verify');