<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MilkEntryController;
use App\Http\Controllers\BillController;

/*
|--------------------------------------------------------------------------
| MilkBook Web Routes
|--------------------------------------------------------------------------
*/

// ── Auth ──────────────────────────────────────────────────────────────────────
Route::get('/',             [AuthController::class, 'showLogin'])->name('login');
Route::get('/login',        [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',       [AuthController::class, 'login'])->name('login.post');
Route::get('/register',     [AuthController::class, 'showRegister'])->name('register');
Route::post('/register',    [AuthController::class, 'register'])->name('register.post');
Route::get('/forgot',       [AuthController::class, 'showForgot'])->name('forgot');
Route::post('/forgot',      [AuthController::class, 'forgot'])->name('forgot.post');
Route::post('/logout',      [AuthController::class, 'logout'])->name('logout');

// ── Protected ─────────────────────────────────────────────────────────────────
Route::middleware('auth.session')->group(function () {

    // Dashboard
    Route::get('/dashboard',    [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile',      [AuthController::class, 'showProfile'])->name('profile');
    Route::post('/profile',     [AuthController::class, 'updateProfile'])->name('profile.update');

    // Customers
    Route::get('/customers',                        [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create',                 [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers',                       [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{id}/edit',              [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{id}',                   [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{id}',                [CustomerController::class, 'destroy'])->name('customers.destroy');
    Route::get('/customers/{id}',                   [CustomerController::class, 'show'])->name('customers.show');

    // Milk Entries
    Route::get('/customers/{customerId}/milk',              [MilkEntryController::class, 'index'])->name('milk.index');
    Route::post('/customers/{customerId}/milk',             [MilkEntryController::class, 'store'])->name('milk.store');
    Route::put('/customers/{customerId}/milk/{entryId}',    [MilkEntryController::class, 'update'])->name('milk.update');
    Route::delete('/customers/{customerId}/milk/{entryId}', [MilkEntryController::class, 'destroy'])->name('milk.destroy');

    // Rates
    Route::post('/customers/{customerId}/rate',     [CustomerController::class, 'saveRate'])->name('rate.save');

    // Bill
    Route::get('/customers/{customerId}/bill',      [BillController::class, 'show'])->name('bill.show');
    Route::get('/customers/{customerId}/bill/pdf',  [BillController::class, 'pdf'])->name('bill.pdf');
});
