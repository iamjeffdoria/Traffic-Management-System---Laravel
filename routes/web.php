<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\TricycleController;
use App\Http\Controllers\Admin\TricycleMayorsPermitController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('admin.dashboard');
    }

    return view('dashboard');
});

Route::get('/ping', function () {
    return response()->json(['message' => 'Laravel is alive']);
});

// Guest-only: show and submit the login form
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

// Auth-only: logout + a protected admin dashboard (any logged-in role can see it)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

// Potpot admin + superadmin only
Route::middleware(['auth', 'role:potpot_admin'])->group(function () {
    Route::get('/potpot', function () {
        return view('admin.potpot'); // create this view when you build the module
    })->name('potpot.index');
});

// Tricycle admin + superadmin only
Route::middleware(['auth', 'role:tricycle_admin'])->group(function () {
    Route::get('/tricycle', function () {
        return view('admin.tricycle'); // create this view when you build the module
    })->name('tricycle.index');

    Route::get('/admin/tricycles', [TricycleController::class, 'index'])->name('tricycle.list');
    Route::post('/admin/tricycles', [TricycleController::class, 'store'])->name('tricycle.store');
    Route::put('/admin/tricycles/{tricycle}', [TricycleController::class, 'update'])->name('tricycle.update');
    Route::delete('/admin/tricycles/{tricycle}', [TricycleController::class, 'destroy'])->name('tricycle.destroy');

    Route::get('/admin/tricycles/mayors-permit', [TricycleMayorsPermitController::class, 'index'])->name('tricycle.mayors-permit');
    Route::post('/admin/tricycles/mayors-permit', [TricycleMayorsPermitController::class, 'store'])->name('tricycle.mayors-permit.store');
    Route::put('/admin/tricycles/mayors-permit/{permit}', [TricycleMayorsPermitController::class, 'update'])->name('tricycle.mayors-permit.update');
});


// Superadmin only
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users');
    Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::put('/admin/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
});