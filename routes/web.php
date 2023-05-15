<?php

use App\Http\Controllers\DataAdministrasiController;
use App\Http\Controllers\InformasiCustomerServiceController;
use App\Http\Controllers\KodeAkunController;
use App\Http\Controllers\KodeIndukController;
use App\Http\Controllers\KodeLedgerController;
use App\Http\Controllers\NasabahController;
use App\Http\Controllers\OtorisasiCustomerServiceController;
use App\Http\Controllers\PembukaanRekeningController;
use App\Http\Controllers\PenarikanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SetorTunaiController;
use App\Http\Controllers\SukuBungaController;
use App\Http\Controllers\UserController;
use App\Models\KodeAkun;
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
        // Master Akuntansi
        Route::prefix('master-akuntansi')->group(function () {
            // Koder ledger
            Route::resource('kode-ledger',KodeLedgerController::class);
            // kode induk
            Route::resource('kode-induk',KodeIndukController::class);
            // kode rekening
            Route::resource('kode-akun',KodeAkunController::class);
        });
        // informasi customer service
        Route::prefix('informasi-customer-service')->group(function () {
            Route::get('informasi-data-nasabah',[InformasiCustomerServiceController::class,'informasiNasabah'])->name('informasi.nasabah');
            Route::get('informasi-data-rekening',[InformasiCustomerServiceController::class,'informasiRekening'])->name('informasi.rekening');

        });
        // otorisasi customer service
        Route::prefix('otorisasi-customer-service')->group(function () {
            Route::post('otorisasi-data-nasabah/ganti-status/post',[OtorisasiCustomerServiceController::class,'postNasabah'])->name('otorisasi.post.nasabah');
            Route::get('otorisasi-data-nasabah/ganti-status',[OtorisasiCustomerServiceController::class,'getNasabah'])->name('otorisasi.get.nasabah');
            Route::get('otorisasi-data-nasabah',[OtorisasiCustomerServiceController::class,'nasabah'])->name('otorisasi.nasabah');
            Route::get('otorisasi-data-rekening',[OtorisasiCustomerServiceController::class,'rekening'])->name('otorisasi.rekening');
        });
        // nasabah
        Route::prefix('customer-service')->group(function () {
            Route::resource('nasabah', NasabahController::class);
            // pembukaan rekening
            Route::get('cetak/{id}',[PembukaanRekeningController::class,'cetak'])->name('cetak-rekening.pembukaan-rekening');
            Route::resource('pembukaan-rekening',PembukaanRekeningController::class);

            // Perubahan data administrasi
            Route::resource('perubahan-data-administrasi', DataAdministrasiController::class);
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
            // akun
            Route::resource('akun', UserController::class);
            // suku bunga
            Route::resource('suku-bunga-koperasi', SukuBungaController::class);
        });
    });
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
