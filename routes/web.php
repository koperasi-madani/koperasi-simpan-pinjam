<?php

use App\Http\Controllers\CadanganBukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataAdministrasiController;
use App\Http\Controllers\DataInformasiPinjamanController;
use App\Http\Controllers\DenominasiController;
use App\Http\Controllers\InformasiAdminKreditController;
use App\Http\Controllers\InformasiCustomerServiceController;
use App\Http\Controllers\InformasiGLController;
use App\Http\Controllers\InformasiHeadTellerController;
use App\Http\Controllers\InformasiNasabahTellerController;
use App\Http\Controllers\KodeAkunController;
use App\Http\Controllers\KodeIndukController;
use App\Http\Controllers\KodeLedgerController;
use App\Http\Controllers\LaporanCustomerServiceController;
use App\Http\Controllers\LaporanNeracaController;
use App\Http\Controllers\NasabahController;
use App\Http\Controllers\OtorisasiCustomerServiceController;
use App\Http\Controllers\PembayaranKasTellerController;
use App\Http\Controllers\PembukaanRekeningController;
use App\Http\Controllers\PeminjamanKasTellerController;
use App\Http\Controllers\PenarikanController;
use App\Http\Controllers\PenerimaanKasTellerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SetorTunaiController;
use App\Http\Controllers\SukuBungaController;
use App\Http\Controllers\TransaksiManyToManyController;
use App\Http\Controllers\TutupCabangController;
use App\Http\Controllers\UserController;
use App\Models\KodeAkun;
use App\Models\Penarikan;
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
Route::get('tampilan', function () {
    return view('pages.setor-tunai.pdf');
});
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
            // PENCAIRAN FASILITAS PINJAMAN
            Route::get('pencairan-fasilitas-pinjaman', function () {
                return view('tampilan');
            })->name('pencairan.fasilitas.pinjaman');
            // PENUTUPAN FASILITAS PINJAMAN
            Route::get('penutupan-fasilitas-pinjaman', function () {
                return view('tampilan');
            })->name('penutupan.fasilitas.pinjaman');
            // informasi pinjaman
            Route::prefix('informasi-pinjaman')->group(function () {
                // informasi pinjaman
                Route::get('data-informasi-pinjaman',[DataInformasiPinjamanController::class,'index'])->name('informasi.pinjaman');
                // informasi data nasabah
                Route::get('informasi-data-nasabah',[InformasiAdminKreditController::class,'informasiNasabah'])->name('informasi.nasabah.admin-kredit');
                // informasi data rekening
                Route::get('informasi-data-rekening',[InformasiAdminKreditController::class,'informasiRekening'])->name('informasi.rekening.admin-kredit');
            });
        });
        // informasi customer service
        Route::prefix('informasi-customer-service')->group(function () {
            Route::get('informasi-data-nasabah',[InformasiCustomerServiceController::class,'informasiNasabah'])->name('customer.informasi.nasabah');
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
            // pemblokiran saldo retail
            Route::get('pemblokiran-saldo-retail', function () {
                return view('tampilan');
            })->name('pemblokiran.saldo-retail');
            // cetak buku tabungan
            Route::get('cetak-buku-tabungan', function () {
                return view('tampilan');
            })->name('cetak.tabungan');
        });
        // Teller
        Route::prefix('teller')->group(function () {
            Route::prefix('transaksi-teller')->group(function () {
                // setor tunai
                Route::get('setor-tunai/pdf/{id}',[SetorTunaiController::class,'pdf'])->name('setor-tunai.pdf');
                Route::resource('setor-tunai', SetorTunaiController::class);
                // Penarikan
                Route::get('penarikan/pdf/{id}',[PenarikanController::class,'pdf'])->name('penarikan.pdf');
                Route::resource('penarikan',PenarikanController::class);
                // peminjaman kas teller (head teller)
                Route::post('pembayaran-kas-teller/peminjaman-kas',[PeminjamanKasTellerController::class,'post'])->name('peminjaman-kas.post');
                // pembayaran kas
                Route::post('pembayaran-kas-teller/post', [PembayaranKasTellerController::class,'post'])->name('pembayaran.kas-teller.post');
                Route::get('pembayaran-kas-teller', [PembayaranKasTellerController::class,'index'])->name('pembayaran.kas-teller');
                // penerimaan kas teller
                Route::post('penerimaan-kas-teller/post',[PenerimaanKasTellerController::class,'post'])->name('penerimaan.kas-teller.post');
                Route::get('penerimaan-kas-teller',[PenerimaanKasTellerController::class,'index'])->name('penerimaan.kas-teller');
                // informasi nasabah
                Route::get('informasi-tabungan-nasabah/{id}',[InformasiNasabahTellerController::class,'informasiNasabahDetail'])->name('teller.informasi.nasabah-detail');
                Route::get('informasi-tabungan-nasabah/penarikan/{id}',[InformasiNasabahTellerController::class,'detailPenarikan'])->name('teller.informasi.nasabah-penarikan');
                Route::get('informasi-tabungan-nasabah',[InformasiNasabahTellerController::class,'informasiNasabah'])->name('teller.informasi.nasabah');
            });
        });
        // informasi head teller
        Route::prefix('informasi-head-teller')->group(function () {
            // informasi semua saldo teller
            Route::get('informasi-semua-saldo-teller',[InformasiHeadTellerController::class,'informasiSemuaSaldo'])->name('informasi.semua-saldo');
            // saldo teller
            Route::get('saldo-teller',[InformasiHeadTellerController::class,'informasiSaldoTeller'])->name('informasi.saldo-teller');
            // informasi tabungan nasabah
            Route::get('informasi-tabungan-nasabah',[InformasiHeadTellerController::class,'informasiNasabah'])->name('informasi.nasabah');
            // denominasi
            Route::post('informasi-denominasi/post',[DenominasiController::class,'post'])->name('informasi.denominasi.post');
            Route::get('informasi-denominasi',[DenominasiController::class,'index'])->name('informasi.denominasi');
        });
        // laporan customer service
        Route::prefix('laporan-customer-service')->group(function () {
            // laporan deposito
            Route::get('laporan-deposito', function () {
                return view('tampilan');
            })->name('laporan.deposito');
            // laporan pembukaan rekening
            Route::get('laporan-pembukaan-rekening/pdf',[LaporanCustomerServiceController::class,'laporanBukaRekeningPdf'])->name('laporan.pembukaan-rekening.pdf');
            Route::get('laporan-pembukaan-rekening',[LaporanCustomerServiceController::class,'laporanBukaRekening'])->name('laporan.pembukaan-rekening');
        });
        // otorisasi transaksi per operator
        Route::prefix('otorisasi-head-teller')->group(function () {
            // otorisasi transaksi per operator
            Route::get('otorisasi-transaksi-per-operator', function () {
                return view('tampilan');
            })->name('otorisasi.transaksi-operator');
        });
        // transaksi back office
        Route::prefix('transaksi-back-office')->group(function () {
            // TRANSAKSI PEMINDAH BUKUAN ANTAR REKENING
            Route::get('transaksi-pemindah-buku-antar-rekening', function () {
                return view('tampilan');
            })->name('transaksi.pemindah.buku.rekening');
            // TRANSAKSI PENDEBETAN DENGAN KODE GL
            Route::get('transaksi-pendebetan-dengan-kode-gl', function () {
                return view('tampilan');
            })->name('transaksi.debet.kode.gl');
            // TRANSAKSI PENGKREDITAN DENGAN KODE GL
            Route::get('transaksi-pengkreditan-dengan-dengan-kode-gl', function () {
                return view('tampilan');
            })->name('transaksi.kredit.kode.gl');
            // TRANSAKSI MANY TO MANY
            Route::get('transaksi-many-to-many/akun',[TransaksiManyToManyController::class,'kodeAkun'])->name('transaksi.kodeAkun');
            Route::resource('transaksi-many-to-many',TransaksiManyToManyController::class);
        });
        // laporan back office
        Route::prefix('laporan-back-office')->group(function () {
            // LAPORAN TRANSAKSI SENDIRI
            Route::get('laporan-transaksi-sendiri', function () {
                return view('tampilan');
            })->name('laporan.transaksi.sendiri');
        });
        // transaksi deposito
        Route::prefix('transaksi-deposito')->group(function () {
            // PENEMPATAN DEPOSITO BERJANGKA
            Route::get('penempatan-deposito-berjangka', function () {
                return view('tampilan');
            })->name('penempatan.deposito-berjangka');
            // PENCAIRAN DEPOSITO BERJANGKA
            Route::get('pencairan-deposito-berjangka', function () {
                return view('tampilan');
            })->name('pencairan-deposito-berjangka');
        });

        // informasi gl
        Route::prefix('informasi-gl')->group(function () {
            // melihat g/l master
            Route::get('melihat-gl-master', function () {
                return view('tampilan');
            })->name('melihat.gl.master');
            // melihat transaksi g/l
            Route::get('melihat-transaksi-gl', function () {
                return view('tampilan');
            })->name('melihat.transaksi.gl');
            // melihat data rekening tabungan
            Route::get('melihat-data-rekening-tabungan', function () {
                return view('tampilan');
            })->name('melihat.data.rekening.tabungan');
            // informasi tabungan
            Route::get('informasi-tabungan-nasabah/{id}',[InformasiGLController::class,'informasiNasabahDetail'])->name('informasi-gl.informasi.nasabah-detail');
            Route::get('informasi-tabungan-nasabah',[InformasiGLController::class,'informasiNasabah'])->name('informasi-gl.informasi.nasabah');

        });
        //laporan
        Route::prefix('laporan')->group(function () {
            // neraca
            Route::get('neraca/cetak',[LaporanNeracaController::class,'cetak'])->name('neraca.cetak');
            Route::get('neraca',[LaporanNeracaController::class,'neraca'])->name('neraca.index');
        });
        // setting
        Route::prefix('setting')->group(function()
        {
            // akun
            Route::resource('akun', UserController::class);
            // suku bunga
            Route::resource('suku-bunga-koperasi', SukuBungaController::class);
            // tutup cabang
            Route::post('tutup-cabang/post',[TutupCabangController::class,'post'])->name('tutup-cabang.post');
            Route::get('tutup-cabang',[TutupCabangController::class,'index'])->name('tutup-cabang.index');
        });
    });
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
