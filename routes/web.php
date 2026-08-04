<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Customer\DashboardCustController;
use App\Http\Controllers\Customer\IklanController as CustomerIklanController;
use App\Http\Controllers\Customer\KeuanganController;
use App\Http\Controllers\Customer\ProdukController;
use App\Http\Controllers\Customer\SetDataController;
use App\Http\Controllers\Customer\TransaksiMenuController;
use App\Http\Controllers\Developer\DashboardController;
use App\Http\Controllers\Developer\DetailPenggunaController;
use App\Http\Controllers\Developer\IklanController;
use App\Http\Controllers\Developer\InformasiPenggunaController;
use App\Http\Controllers\Developer\KelolaPenggunaMenuController;
use App\Http\Controllers\Developer\LupaPassword;
use App\Http\Controllers\Developer\NotificationController;
use App\Http\Controllers\Developer\PengeluaranController;
use App\Http\Controllers\Developer\PenghasilanController;
use App\Http\Controllers\Developer\Penyewaan;
use App\Http\Controllers\Developer\ProfileController;
use App\Http\Controllers\Developer\RekapKeuanganController;
use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index']);
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::prefix('lupa-password')->name('lupa-password.')->group(function () {
        Route::get('/', [LupaPassword::class, 'index'])->name('index');
        Route::post('/send-otp', [LupaPassword::class, 'sendOTP'])->name('send-otp');

        Route::get('/check-kode-otp/{nomor_telephone}', [LupaPassword::class, 'indexCheckOTP'])->name('kode-otp');
        Route::post('/check-kode-otp/{nomor_telephone}', [LupaPassword::class, 'checkOTP'])->name('check-otp');

        Route::post('/kirim-ulang/{nomor_telephone}', [LupaPassword::class, 'kirimUlang'])->name('kirim-ulang');

        Route::get('/reset-password/{nomor_telephone}', [LupaPassword::class, 'indexResetPassword'])->name('reset-password');
        Route::post('/reset-password/{nomor_telephone}', [LupaPassword::class, 'resetPassword'])->name('change-password');
    });
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| Landing Page Routes
|--------------------------------------------------------------------------
*/

Route::controller(LandingPageController::class)->group(function () {
    Route::get('/', 'halamanBeranda')->name('landing-page.halaman-beranda');
    Route::get('/halaman-destinasi', 'halaman_destinasi')->name('landing-page.halaman-destinasi');
    Route::get('/halaman-sewabarang', 'halaman_sewabarang')->name('landing-page.halaman-sewabarang');
    Route::get('/halaman-testimoni', 'halaman_testimoni')->name('landing-page.halaman-testimoni');
    Route::get('/halaman-tentangkami', 'halaman_tentangkami')->name('landing-page.halaman-tentangkami');
});


/*
|--------------------------------------------------------------------------
| Developer Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Developer Dashboard
    |--------------------------------------------------------------------------
    */

    Route::prefix('developer/dashboard')->group(function () {

        Route::get('/home', [DashboardController::class, 'index'])->name('home.index');

        /*
        |--------------------------------------------------------------------------
        | Notification
        |--------------------------------------------------------------------------
        */

        Route::prefix('notification')
            ->name('notification.')
            ->controller(NotificationController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');

                Route::post('/balas-semua-feedback', 'balasSemuaFeedback')
                    ->name('balas-semua-feedback');

                Route::post('/balas-terpilih-feedback', 'balasTerpilihFeedback')
                    ->name('balas-terpilih-feedback');

                Route::delete('/delete-selected-feedback', 'deleteSelectedFeedback')
                    ->name('delete-selected-feedback');

                Route::delete('/delete-selected-reply', 'deleteSelectedReply')
                    ->name('delete-selected-reply');

                Route::get('/feedback/{feedback}/messages', 'messages')
                    ->name('feedback-messages.index');

                Route::post('/feedback/{feedback}/messages', 'storeMessage')
                    ->name('feedback-messages.store');

                Route::get('/customer-replies', 'customerReplies')
                    ->name('customer-replies');
            });

        /*
        |--------------------------------------------------------------------------
        | Kelola Pengguna
        |--------------------------------------------------------------------------
        */

        Route::prefix('kelola-pengguna')->group(function () {
            Route::get('/', [KelolaPenggunaMenuController::class, 'index'])
                ->name('kelola-pengguna.index');

            Route::post('/', [KelolaPenggunaMenuController::class, 'store'])
                ->name('kelola-pengguna.store');

            Route::delete('/delete-selected', [KelolaPenggunaMenuController::class, 'bulkDestroy'])
                ->name('kelola-pengguna.bulk-destroy');

            Route::delete('/{id}', [KelolaPenggunaMenuController::class, 'destroy'])
                ->name('kelola-pengguna.destroy');

            Route::prefix('detail-pengguna/{user}')
                ->name('detail-pengguna.')
                ->controller(DetailPenggunaController::class)
                ->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/produk-disewakan', 'showProdukDisewakan')->name('produk-disewakan');
                    Route::get('/produk-disewakan/detail-produk/{namaproduk}', 'showDetailProdukDisewakan')->name('detail-produk-disewakan');
                    Route::get('/detail-produk-sedang-disewa/{namaproduk}', 'showDetailProdukSedangDisewa')->name('detail-produk-sedang-disewa');
                });

            Route::put('/detail/{id}', [DetailPenggunaController::class, 'update'])
                ->name('kelola-pengguna.update');

            Route::delete('/detail/{id}', [DetailPenggunaController::class, 'destroy'])
                ->name('kelola-pengguna.detail-destroy');
        });

        Route::get('/informasi-pengguna', [InformasiPenggunaController::class, 'index'])
            ->name('informasi-pengguna.index');

        /*
        |--------------------------------------------------------------------------
        | Iklan Developer
        |--------------------------------------------------------------------------
        */

        Route::prefix('iklan')->name('iklan.')->controller(IklanController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::delete('/delete-iklan-pending/{id_iklan}', 'deleteIklanPending')->name('delete-iklan-pending');
            Route::put('/aktivasi/{id_detail_iklan}', 'aktivasiIklan')->name('aktivasi');
            Route::put('/nonaktifkan/{id_detail_iklan}', 'nonaktifkanIklan')->name('nonaktifkan');
            Route::put('/selesaikan/{id_detail_iklan}', 'selesaikanIklan')->name('selesaikan');
        });

        /*
        |--------------------------------------------------------------------------
        | Penyewaan
        |--------------------------------------------------------------------------
        */

        Route::get('/penyewaan', [Penyewaan::class, 'index'])->name('penyewaan.index');

        /*
        |--------------------------------------------------------------------------
        | Keuangan Developer
        |--------------------------------------------------------------------------
        */

        Route::get('/penghasilan', [PenghasilanController::class, 'index'])->name('penghasilan.index');
        Route::post('/penghasilan/tambah-penghasila-dev/{id_user}', [PenghasilanController::class, 'tambahPenghasilan'])
            ->name('penghasilan.tambah-penghasilan-dev');

        Route::get('/pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran.index');

        Route::get('/rekap-keuangan', [RekapKeuanganController::class, 'index'])->name('rekap-keuangan.index');

        /*
        |--------------------------------------------------------------------------
        | Profile Developer
        |--------------------------------------------------------------------------
        */

        Route::get('/profile/{nama_lengkap}', [ProfileController::class, 'index'])->name('profile.index');
    });

    /*
    |--------------------------------------------------------------------------
    | Developer Action Routes Outside Prefix
    |--------------------------------------------------------------------------
    */

    Route::put('/mark-notification-as-read', [DashboardController::class, 'markNotificationAsRead'])
        ->name('mark-notification-as-read');

    Route::post('/delete-selected-products', [DetailPenggunaController::class, 'deleteSelectedProducts'])
        ->name('delete-selected-products');
});


/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->prefix('customer/dashboard')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Customer Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/home/{id_user?}', [DashboardCustController::class, 'index'])
        ->name('dashboard-cust');

    /*
    |--------------------------------------------------------------------------
    | Produk Customer
    |--------------------------------------------------------------------------
    */

    Route::prefix('menu-produk')->name('menu-produk.')->controller(ProdukController::class)->group(function () {
        Route::get('/{id_user}', 'index')->name('index');
    });

    Route::prefix('kelola-produk')->name('menu-produk.')->controller(ProdukController::class)->group(function () {
        Route::get('/{id_user}', 'kelolaProduk')->name('kelola-produk');
        Route::get('/detail-produk/{nama_produk}/{id_user}', 'detailProduk')->name('detail-produk');
        Route::get('/tambah-produk/{id_user}', 'tambahProduk')->name('tambah-produk');
        Route::get('/update-produk/{id_produk}/{id_user}', 'updateProduk')->name('update-produk');

        Route::post('/tambah-produk-post', 'tambahProdukPost')->name('tambah-produk-post');
        Route::put('/update-produk-put/{id_produk}', 'updateProdukPut')->name('update-produk-put');
    });

    Route::get('/sedang-disewa/{id_user}', [ProdukController::class, 'sedangDisewa'])
        ->name('menu-produk.sedang-disewa');

    Route::delete('/delete-produk/{id_produk}', [ProdukController::class, 'deleteProduk'])
        ->name('menu-produk.delete');

    Route::get('/detail-produk/{id_produk}', [ProdukController::class, 'detailProduk'])
        ->name('menu-produk.detail-produk');

    /*
    |--------------------------------------------------------------------------
    | Iklan Customer
    |--------------------------------------------------------------------------
    */

    Route::controller(CustomerIklanController::class)->group(function () {
        Route::get('/buat-iklan/{id_user}', 'index')->name('buat-iklan.index');

        Route::get('/kelola-iklan/{id_user}', 'kelolaIklan')->name('kelola-iklan.index');
        Route::get('/kelola-iklan/update-iklan/{id_iklan}', 'updateIklanView')->name('kelola-iklan.update-iklan-view');
        Route::put('/kelola-iklan/update-iklan-post/{id_iklan}/{id_user}', 'updateIklan')->name('kelola-iklan.update-iklan-post');

        Route::get('/pilih-durasi-iklan/{id_user}', 'pilihDurasiIklan')->name('pilih-durasi-iklan.index');
        Route::get('/layanan-iklan/{id_user}/{harga_iklan}', 'layananIklan')->name('layanan-iklan.index');
        Route::post('/layanan-iklan/{id_user}/{harga_iklan}/{durasi}', 'simpanIklan')->name('layanan-iklan.simpan-iklan');

        Route::get('/input-pembayaran-iklan/{id_user}/{harga_iklan}/{durasi}', 'inputPembayaranIklan')->name('input-pembayaran-iklan.index');
        Route::post('/simpan-pembayaran-iklan/{id_user}', 'simpanPembayaranIklan')->name('simpan-pembayaran-iklan.simpan');
    });

    /*
    |--------------------------------------------------------------------------
    | Keuangan Customer
    |--------------------------------------------------------------------------
    */

    Route::prefix('keuangan')->name('keuangan.')->controller(KeuanganController::class)->group(function () {
        Route::get('/{id_user}', 'index')->name('index');

        Route::post('/tambah-penghasilan/{id_user}', 'tambahPenghasilan')->name('tambah-penghasilan');
        Route::get('/update-penghasilan/{id_penghasilan}', 'updatePenghasilan')->name('update-penghasilan');
        Route::put('/update-penghasilan-post/{id_penghasilan}/{id_user}', 'updatePenghasilanPost')->name('update-penghasilan-post');
        Route::delete('/delete-penghasilan/{id_penghasilan}/', 'deletePenghasilan')->name('delete-penghasilan');

        Route::get('/pengeluaran/{id_user}', 'pengeluaran')->name('pengeluaran-customer');
        Route::post('/tambah-pengeluaran/{id_user}', 'tambahPengeluaran')->name('tambah-pengeluaran-customer');
        Route::get('/update-pengeluaran/{id_pengeluaran}', 'updatePengeluaran')->name('update-pengeluaran-customer');
        Route::put('/update-pengeluaran-post/{id_pengeluaran}/{id_user}', 'updatePengeluaranPost')->name('update-pengeluaran-post-customer');
        Route::delete('/delete-pengeluaran/{id_pengeluaran}', 'deletePengeluaran')->name('delete-pengeluaran-customer');
    });

    /*
    |--------------------------------------------------------------------------
    | Transaksi Customer
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\Customer\KasirOfflineController::class)->name('kasir-offline.')->group(function () {
        Route::get('/kasir-offline/{id_user}', 'index')->name('index');
        Route::post('/kasir-offline/proses/{id_user}', 'prosesPesanan')->name('proses');
    });

    Route::controller(TransaksiMenuController::class)->name('menu-transaksi.')->group(function () {
        Route::get('/transaksi/{id_user}', 'index')->name('index');
        Route::get('/sewa-berlangsung/{id_user}', 'sewaBerlangsung')->name('sewa-berlangsung');
        Route::get('/denda-transaksi/{id_user}', 'dendaTransaksi')->name('denda-transaksi');
        Route::get('/order-selesai/{id_user}', 'orderSelesai')->name('order-selesai');

        Route::get('/transaksi/terima-order-masuk/{id_penyewaan}', 'terimaOrderMasuk')->name('terima-order-masuk');
        Route::put('/transaksi/input-pembayaran-cod/{id_penyewaan}', 'inputPembayaranCOD')->name('input-pembayaran-cod');
        Route::put('/transaksi/confirm-order-masuk/{id_penyewaan}/{id_user}/{parameter}', 'confirmOrderMasuk')->name('confirm-order-masuk');
        Route::put('/transaksi/batalkan-order/{id_penyewaan}/{id_user}', 'batalkanOrder')->name('batalkan-order');
        Route::post('/transaksi/proses-pengembalian/{id_penyewaan}/{id_user}', 'prosesPengembalian')->name('proses-pengembalian');
    });
});


/*
|--------------------------------------------------------------------------
| Customer Action Routes Outside Prefix
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::delete('/delete-kelola-iklan/{id_iklan}', [CustomerIklanController::class, 'deleteKelolaIklan'])
        ->name('kelola-iklan.delete');

    Route::get('/download-pdf-penghasilan/{id_user}/{tahun}', [KeuanganController::class, 'downloadPenghasilan'])
        ->name('keuangan.download-penghasilan');

    Route::get('/download-pdf-pengeluaran/{id_user}/{tahun}', [KeuanganController::class, 'downloadPengeluaran'])
        ->name('keuangan.download-pengeluaran-customer');
});
