<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\AdminSetupController;
use App\Http\Controllers\AdminUserController;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('welcome'));

/*
|--------------------------------------------------------------------------
| Guest Only – First Admin Setup
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/setup-admin', [AdminSetupController::class, 'create'])->name('setup.admin');
    Route::post('/setup-admin', [AdminSetupController::class, 'store'])->name('setup.admin.store');
});

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', fn () => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Authenticated Users
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Tasks
    |--------------------------------------------------------------------------
    */
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::put('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.update.status');

    // Admin + Super User (authorization handled in controller)
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Only
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/admin/dashboard', fn () => view('admin.dashboard'))->name('admin.dashboard');

    // Audit Logs
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit.logs');

    /*
    |--------------------------------------------------------------------------
    | Manage Users (with Soft Delete / Archive)
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin/users')->group(function () {

        Route::get('/', [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::put('/{user}/block', [AdminUserController::class, 'block'])->name('admin.users.block');
        Route::put('/{user}/unblock', [AdminUserController::class, 'unblock'])->name('admin.users.unblock');
        Route::put('/{user}/promote', [AdminUserController::class, 'promote'])->name('admin.users.promote');
        Route::put('/{user}/demote', [AdminUserController::class, 'demote'])->name('admin.users.demote');
        Route::delete('/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
    });

});

require __DIR__ . '/auth.php';
