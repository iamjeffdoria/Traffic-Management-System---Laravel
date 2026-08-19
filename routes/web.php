<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\TricycleController;
use App\Http\Controllers\Admin\TricycleMayorsPermitController;
use App\Http\Controllers\Admin\MtopController;
use App\Http\Controllers\Admin\FranchiseController;

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
    Route::delete('/admin/tricycles/mayors-permit/{permit}', [TricycleMayorsPermitController::class, 'destroy'])->name('tricycle.mayors-permit.destroy');

    Route::get('/admin/tricycles/mtop', [MtopController::class, 'index'])->name('tricycle.mtop');
    Route::post('/admin/tricycles/mtop', [MtopController::class, 'store'])->name('tricycle.mtop.store');
    Route::put('/admin/tricycles/mtop/{mtop}', [MtopController::class, 'update'])->name('tricycle.mtop.update');
    Route::delete('/admin/tricycles/mtop/{mtop}', [MtopController::class, 'destroy'])->name('tricycle.mtop.destroy');
    Route::get('/admin/tricycles/mtop/{mtop}/print', [MtopController::class, 'print'])->name('tricycle.mtop.print');

    Route::get('/admin/tricycles/franchise', [FranchiseController::class, 'index'])->name('tricycle.franchise');
    Route::post('/admin/tricycles/franchise', [FranchiseController::class, 'store'])->name('tricycle.franchise.store');
    Route::put('/admin/tricycles/franchise/{franchise}', [FranchiseController::class, 'update'])->name('tricycle.franchise.update');
    Route::delete('/admin/tricycles/franchise/{franchise}', [FranchiseController::class, 'destroy'])->name('tricycle.franchise.destroy');
    Route::get('/admin/tricycles/franchise/{franchise}/print', [FranchiseController::class, 'print'])->name('tricycle.franchise.print');
});


// Superadmin only
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users');
    Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::put('/admin/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
});