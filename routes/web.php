<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ComplaintController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return response()->json([
        'name' => config('app.name'),
        'status' => 'running',
    ]);
});

Route::redirect('/login', '/admin/login');
Route::redirect('/register', '/admin/login');

Route::prefix('admin')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', function (Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ])->onlyInput('email');
        }

        if (Auth::user()->isBanned()) {
            Auth::logout();

            return back()->withErrors([
                'email' => 'Akun ini sedang diban.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect('/admin/dashboard');
    });

    Route::post('/logout', function (Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    })->name('admin.logout');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('admin')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');

    // Complaints
    Route::get('/complaints', [ComplaintController::class, 'index'])
        ->name('admin.complaints.index');
    Route::post('/complaints', [ComplaintController::class, 'store'])
        ->name('admin.complaints.store');
    Route::get('/complaints/{complaint}', [ComplaintController::class, 'show'])
        ->name('admin.complaints.show');
    Route::put('/complaints/{complaint}', [ComplaintController::class, 'update'])
        ->name('admin.complaints.update');
    Route::delete('/complaints/{complaint}', [ComplaintController::class, 'destroy'])
        ->name('admin.complaints.destroy');

    Route::get('/categories', [CategoryController::class, 'index'])
        ->name('admin.categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])
        ->name('admin.categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])
        ->name('admin.categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])
        ->name('admin.categories.destroy');

    Route::get('/users', [UserController::class, 'index'])
        ->name('admin.users.index');
    Route::post('/users', [UserController::class, 'store'])
        ->name('admin.users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])
        ->name('admin.users.update');
    Route::post('/users/{user}/ban', [UserController::class, 'ban'])
        ->name('admin.users.ban');
    Route::post('/users/{user}/unban', [UserController::class, 'unban'])
        ->name('admin.users.unban');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])
        ->name('admin.users.destroy');
});
