<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ComplaintController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ComplaintResponseController;
use App\Http\Controllers\Admin\ComplaintAttachmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

Route::get('/', function () {
    return response()->json([
        'name' => config('app.name'),
        'status' => 'running',
    ]);
});

Route::get('/api/documentation', function () {
    return view('api-docs');
})->name('api.documentation');

Route::get('/api/documentation/openapi.json', function () {
    $path = base_path('docs/openapi.json');

    abort_unless(File::exists($path), 404);

    return response()->file($path, [
        'Content-Type' => 'application/json',
    ]);
})->name('api.documentation.openapi');

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

        if (! Auth::user()->canAccessAdminPanel()) {
            Auth::logout();

            return back()->withErrors([
                'email' => 'Hanya admin aktif yang boleh login ke panel ini.',
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

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::get('/audit-logs', [AuditLogController::class, 'index'])
        ->middleware('super_admin')
        ->name('admin.audit-logs.index');

    // Complaints
    Route::get('/complaints', [ComplaintController::class, 'index'])
        ->name('admin.complaints.index');
    Route::post('/complaints', [ComplaintController::class, 'store'])
        ->name('admin.complaints.store');
    Route::get('/complaints/{complaint}', [ComplaintController::class, 'show'])
        ->name('admin.complaints.show');
    Route::post('/complaints/{complaint}/responses', [ComplaintResponseController::class, 'store'])
        ->name('admin.complaint-responses.store');
    Route::put('/complaints/{complaint}/responses/{response}', [ComplaintResponseController::class, 'update'])
        ->name('admin.complaint-responses.update');
    Route::delete('/complaints/{complaint}/responses/{response}', [ComplaintResponseController::class, 'destroy'])
        ->name('admin.complaint-responses.destroy');
    Route::post('/complaints/{complaint}/attachments', [ComplaintAttachmentController::class, 'store'])
        ->name('admin.complaint-attachments.store');
    Route::put('/complaints/{complaint}/attachments/{attachment}', [ComplaintAttachmentController::class, 'update'])
        ->name('admin.complaint-attachments.update');
    Route::delete('/complaints/{complaint}/attachments/{attachment}', [ComplaintAttachmentController::class, 'destroy'])
        ->name('admin.complaint-attachments.destroy');
    Route::get('/complaints/{complaint}/attachments/{attachment}/file', [ComplaintAttachmentController::class, 'file'])
        ->name('admin.complaint-attachments.file');
    Route::post('/complaints/{complaint}/attachments/{attachment}/validate', [ComplaintAttachmentController::class, 'validateAttachment'])
        ->name('admin.complaint-attachments.validate');
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
    Route::post('/users', [UserController::class, 'storeAppUser'])
        ->name('admin.users.store');
    Route::put('/users/{user}', [UserController::class, 'updateAppUser'])
        ->name('admin.users.update');
    Route::post('/users/{user}/ban', [UserController::class, 'banAppUser'])
        ->name('admin.users.ban');
    Route::post('/users/{user}/unban', [UserController::class, 'unbanAppUser'])
        ->name('admin.users.unban');
    Route::delete('/users/{user}', [UserController::class, 'destroyAppUser'])
        ->name('admin.users.destroy');

    Route::middleware('super_admin')->group(function () {
        Route::get('/admin-users', [UserController::class, 'adminIndex'])
            ->name('admin.admin-users.index');
        Route::post('/admin-users', [UserController::class, 'storeAdminUser'])
            ->name('admin.admin-users.store');
        Route::put('/admin-users/{user}', [UserController::class, 'updateAdminUser'])
            ->name('admin.admin-users.update');
        Route::post('/admin-users/{user}/ban', [UserController::class, 'banAdminUser'])
            ->name('admin.admin-users.ban');
        Route::post('/admin-users/{user}/unban', [UserController::class, 'unbanAdminUser'])
            ->name('admin.admin-users.unban');
        Route::delete('/admin-users/{user}', [UserController::class, 'destroyAdminUser'])
            ->name('admin.admin-users.destroy');
    });
});
