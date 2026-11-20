<?php

use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\LapanganController;
use App\Http\Controllers\SlotController;
use Illuminate\Support\Facades\Route;

// Halaman Publik
Route::get('/', [LapanganController::class, 'index'])->name('home');
Route::get('/lapangan/{lapangan}', [LapanganController::class, 'jadwal'])->name('lapangan.jadwal');
Route::get('/api/lapangan/{lapangan}/slot', [SlotController::class, 'index'])
    ->middleware('throttle:60,1')
    ->name('api.slot');

// Tamu (Belum Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/daftar', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/daftar', [AuthController::class, 'register']);
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Pelanggan
Route::middleware(['auth', 'role:pelanggan'])->group(function () {
    Route::get('/booking/create', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
    Route::get('/booking/{booking:kode_booking}', [BookingController::class, 'show'])->name('booking.show');
    Route::post('/booking/{booking}/bayar', [BookingController::class, 'bayar'])->name('booking.bayar');
    Route::patch('/booking/{booking}/batal', [BookingController::class, 'batal'])->name('booking.batal');
});

// Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/booking', [AdminBookingController::class, 'index'])->name('booking.index');
    Route::patch('/booking/{booking}/verifikasi', [AdminBookingController::class, 'verifikasi'])->name('booking.verifikasi');
    Route::patch('/booking/{booking}/batal', [AdminBookingController::class, 'batal'])->name('booking.batal');
});