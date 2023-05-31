<?php

use App\Http\Controllers\CadanganBukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataAdministrasiController;
use App\Http\Controllers\InformasiAdminKreditController;
use App\Http\Controllers\InformasiCustomerServiceController;
use App\Http\Controllers\InformasiHeadTellerController;
use App\Http\Controllers\InformasiNasabahTellerController;
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



Route::get('test',[CadanganBukuController::class,'cadangSuku']);

// cek tabungan
Route::get('penarikan/cek',[PenarikanController::class,'cekTabungan'])->name('cek.tabungan');

Route::middleware(['auth'])->group(function () {
    Route::get('/',[DashboardController::class,'index'])->name('dashboard');
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
        Route::prefix('admin-kredit')->group(function () {
            Route::prefix('informasi-pinjaman')->group(function () {
                Route::get('informasi-data-nasabah',[InformasiAdminKreditController::class,'informasiNasabah'])->name('informasi.nasabah.admin-kredit');
                Route::get('informasi-data-rekening',[InformasiAdminKreditController::class,'informasiRekening'])->name('informasi.rekening.admin-kredit');
            });
        });
        // informasi customer service
        Route::prefix('informasi-customer-service')->group(function () {
            Route::get('informasi-data-nasabah',[InformasiCustomerServiceController::class,'informasiNasabah'])->name('informasi.nasabah');
            Route::get('informasi-data-rekening',[InformasiCustomerServiceController::class,'informasiRekening'])->name('informasi.rekening');

        });
        // otorisasi customer service
        Route::prefix('otorisasi-customer-service')->group(function () {
            // otorisasi data nasabah
            Route::post('otorisasi-data-nasabah/ganti-status/post',[OtorisasiCustomerServiceController::class,'postNasabah'])->name('otorisasi.post.nasabah');
            Route::get('otorisasi-data-nasabah/ganti-status',[OtorisasiCustomerServiceController::class,'getNasabah'])->name('otorisasi.get.nasabah');
            Route::get('otorisasi-data-nasabah',[OtorisasiCustomerServiceController::class,'nasabah'])->name('otorisasi.nasabah');
            // otorisasi rekening
            Route::post('otorisasi-data-rekening/ganti-status/post',[OtorisasiCustomerServiceController::class,'postRekening'])->name('otorisasi.post.rekening');
            Route::get('otorisasi-data-rekening/ganti-status',[OtorisasiCustomerServiceController::class,'getRekening'])->name('otorisasi.get.rekening');
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
                // informasi nasabah
                Route::get('informasi-tabungan-nasabah/{id}',[InformasiNasabahTellerController::class,'informasiNasabahDetail'])->name('teller.informasi.nasabah-detail');
                Route::get('informasi-tabungan-nasabah/penarikan/{id}',[InformasiNasabahTellerController::class,'detailPenarikan'])->name('teller.informasi.nasabah-penarikan');
                Route::get('informasi-tabungan-nasabah',[InformasiNasabahTellerController::class,'informasiNasabah'])->name('teller.informasi.nasabah');
            });
        });
        Route::prefix('informasi-head-teller')->group(function () {
            Route::get('informasi-tabungan-nasabah',[InformasiHeadTellerController::class,'informasiNasabah'])->name('informasi.nasabah');
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
