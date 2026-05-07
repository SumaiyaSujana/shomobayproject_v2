<?php

use App\Http\Controllers\AdminVendorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NeighborProfileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VendorProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::get('/neighbor/profile', [NeighborProfileController::class, 'edit'])
        ->name('neighbor.profile.edit');

    Route::patch('/neighbor/profile', [NeighborProfileController::class, 'update'])
        ->name('neighbor.profile.update');

    Route::get('/vendor/profile', [VendorProfileController::class, 'edit'])
        ->name('vendor.profile.edit');

    Route::patch('/vendor/profile', [VendorProfileController::class, 'update'])
        ->name('vendor.profile.update');

    Route::get('/admin/vendors', [AdminVendorController::class, 'index'])
        ->name('admin.vendors.index');

    Route::patch('/admin/vendors/{vendorProfile}/approve', [AdminVendorController::class, 'approve'])
        ->name('admin.vendors.approve');

    Route::patch('/admin/vendors/{vendorProfile}/mark-pending', [AdminVendorController::class, 'markPending'])
        ->name('admin.vendors.mark-pending');
});

require __DIR__ . '/auth.php';