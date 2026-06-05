<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ComplaintController as AdminComplaintController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| ADMIN MIDDLEWARE
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/dashboard', [DashboardController::class, 'index']);

    /*
    |--------------------------------------------------------------------------
    | COMPLAINT MANAGEMENT
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/complaints', [AdminComplaintController::class, 'index']);
    Route::get('/admin/complaints/{id}', [AdminComplaintController::class, 'show']);
    Route::post('/admin/complaints/{id}/status', [AdminComplaintController::class, 'updateStatus']);
    Route::post('/admin/complaints/{id}/response', [AdminComplaintController::class, 'response']);

    /*
    |--------------------------------------------------------------------------
    | CATEGORY MANAGEMENT
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/categories', [AdminCategoryController::class, 'index']);
    Route::post('/admin/categories', [AdminCategoryController::class, 'store']);

    /*
    |--------------------------------------------------------------------------
    | USER MANAGEMENT
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/users', [UserController::class, 'index']);
});
