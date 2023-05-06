<?php

use App\Http\Controllers\NasabahController;
use App\Http\Controllers\PembukaanRekeningController;
use App\Http\Controllers\PenarikanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SetorTunaiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// cek tabungan
Route::get('penarikan/cek',[PenarikanController::class,'cekTabungan'])->name('cek.tabungan');

Route::middleware(['auth'])->group(function () {
    Route::prefix('dashboard')->group(function () {
        // nasabah
        Route::prefix('customer-service')->group(function () {
            Route::resource('nasabah', NasabahController::class);
            // pembukaan rekening
            Route::get('cetak/{id}',[PembukaanRekeningController::class,'cetak'])->name('cetak-rekening.pembukaan-rekening');
            Route::resource('pembukaan-rekening',PembukaanRekeningController::class);
        });
        // Teller
        Route::prefix('teller')->group(function () {
            Route::prefix('transaksi-teller')->group(function () {
                Route::resource('setor-tunai', SetorTunaiController::class);
                Route::resource('penarikan',PenarikanController::class);
            });
        });
        // setting
        Route::prefix('setting')->group(function()
        {
            Route::resource('akun', UserController::class);
        });
    });
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
