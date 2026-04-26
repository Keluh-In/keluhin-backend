<?php

use App\Models\Complaint;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ComplaintController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

Route::get('/', function () {
    return response()->json([
        'name' => config('app.name'),
        'status' => 'running',
    ]);
});

Route::redirect('/login', '/admin/login');
Route::redirect('/register', '/admin/register');

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

        $request->session()->regenerate();

        return redirect('/admin/dashboard');
    });

    Route::get('/register', function () {
        return view('auth.register');
    })->name('admin.register');

    Route::post('/register', function (Request $request) {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['nullable', 'in:admin,user'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'] ?? 'user',
        ]);

        Auth::login($user);
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
    Route::delete('/users/{user}', [UserController::class, 'destroy'])
        ->name('admin.users.destroy');
});
