<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HousingController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MosqueController;
use App\Http\Controllers\MosqueDashboardController;
use App\Http\Controllers\NeighborhoodController;
use App\Http\Controllers\ProfessionController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Authentication Routes (Guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Logout Route (Authenticated only)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes (Require Authentication)
Route::middleware('auth')->group(function () {
    // Dashboard Routes
    Route::get('/', [MosqueDashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [MosqueDashboardController::class, 'index'])->name('dashboard');

    // Resource Routes
    Route::resource('neighborhoods', NeighborhoodController::class);
    Route::resource('provinces', ProvinceController::class);
    Route::resource('mosques', MosqueController::class);
    Route::resource('members', MemberController::class);
    Route::resource('housing', HousingController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('professions', ProfessionController::class);

    // User & Role Management (Super Admin Only)
    Route::middleware('role:super-admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
    });

    // Export Routes
    Route::get('/members/export', [MemberController::class, 'export'])->name('members.export');
    Route::get('/members/export/all', [MemberController::class, 'exportAll'])->name('members.export.all');
    Route::get('/mosques/{mosque}/members/export', [MemberController::class, 'exportByMosque'])->name('mosques.members.export');
    Route::get('/members/{member}/export', [MemberController::class, 'exportSingle'])->name('members.export.single');

    // Document Routes
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::post('/members/{member}/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

    // Transfer Routes
    Route::get('/members/{member}/transfer', [TransferController::class, 'transferForm'])->name('members.transfer');
    Route::post('/members/{member}/transfer', [TransferController::class, 'transfer'])->name('members.transfer.store');
    Route::get('/members/{member}/change-category', [TransferController::class, 'changeCategoryForm'])->name('members.change-category');
    Route::post('/members/{member}/change-category', [TransferController::class, 'changeCategory'])->name('members.change-category.store');
    Route::get('/members/{member}/transfer-history', [TransferController::class, 'history'])->name('members.transfer-history');

    // API Routes for quick add (used in modals)
    Route::post('/api/categories', [CategoryController::class, 'storeApi'])->name('api.categories.store');
    Route::post('/api/professions', [ProfessionController::class, 'storeApi'])->name('api.professions.store');
    Route::post('/api/mosques', [MosqueController::class, 'storeApi'])->name('api.mosques.store');
});
