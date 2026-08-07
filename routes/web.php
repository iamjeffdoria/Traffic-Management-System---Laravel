<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
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
});

// Superadmin only
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/admin/users', function () {
        return view('admin.users'); // create this view when you build the module
    })->name('admin.users');
});