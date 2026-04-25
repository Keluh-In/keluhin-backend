<?php

use App\Models\Complaint;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ComplaintController;

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('admin')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');

    // Complaints (PAKAI CONTROLLER - CLEAN)
    Route::get('/complaints', [ComplaintController::class, 'index'])
        ->name('admin.complaints.index');

    Route::get('/categories', function () {
        return view('admin.categories.index');
    });

    Route::get('/users', function () {
        return view('admin.users.index');
    });
});


