<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', fn() => view('welcome'));

// Auth Routes
Route::get('/login', fn() => view('auth.login'))->name('login')->middleware('guest');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email'    => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }

    return back()->withErrors(['email' => 'These credentials do not match our records.'])->onlyInput('email');
})->middleware('guest');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// Protected Routes - Require Authentication
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', fn() => view('dashboard'));

    // Members Routes
    Route::prefix('members')->group(function () {
        Route::get('/', fn() => view('members.index'));
        Route::get('/create', fn() => view('members.create'));
        Route::get('/{id}', fn() => view('members.show'));
        Route::get('/{id}/edit', fn() => view('members.edit'));
    });

    // Investments Routes
    Route::prefix('investments')->group(function () {
        Route::get('/', fn() => view('investments.index'));
        Route::get('/create', fn() => view('investments.create'));
    });

    // Loans Routes
    Route::prefix('loans')->group(function () {
        Route::get('/', fn() => view('loans.index'));
        Route::get('/create', fn() => view('loans.create'));
        Route::get('/{id}', fn() => view('loans.show'));
    });

    // Repayments Routes
    Route::prefix('repayments')->group(function () {
        Route::get('/', fn() => view('repayments.index'));
        Route::get('/create', fn() => view('repayments.create'));
    });

    // Reports Routes
    Route::get('/reports', fn() => view('reports.index'));

    // Settings Routes
    Route::get('/settings', fn() => view('settings.index'));
});
