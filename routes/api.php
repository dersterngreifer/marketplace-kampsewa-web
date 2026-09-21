<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LupaPassword;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\ChartWebController;
use App\Http\Controllers\Api\FeedbackChatController;
use App\Http\Controllers\Api\IklanControlller;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RiwayatPencarianController;
use App\Http\Controllers\Api\TransaksiController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Auth Routes
|--------------------------------------------------------------------------
*/

Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register']);


/*
|--------------------------------------------------------------------------
| API Lupa Password Routes
|--------------------------------------------------------------------------
*/

Route::prefix('lupa-password')
    ->controller(LupaPassword::class)
    ->group(function () {
        Route::post('/', 'verifikasiPhone');
        Route::post('/verifikasi-otp/{nomor_telephone}', 'verifikasiOTP');
        Route::post('/reset-password/{nomor_telephone}', 'resetPassword');
        Route::post('/kirim-ulang-otp/{nomor_telephone}', 'kirimUlangOTP');
    });


/*
|--------------------------------------------------------------------------
| API Chart Web Routes
|--------------------------------------------------------------------------
*/

Route::controller(ChartWebController::class)->group(function () {
    Route::get('/chart-keuntungan-menu-dashboard', 'ApiTotalKeuntungan');
    Route::get('/chart-penghasilan-menu-penghasilan', 'apiChartMenuPenghasilan');
    Route::get('/chart-penghasilan-perbulan-menu-penghasilan', 'apiChartTotalPenghasilanPerbulanSaatIniMenuPenghasilan');
    Route::get('/chart-perbandingan-pertahun-web-cust/{id_user}', 'apiPerbandinganPemasukanPertahunWebCust');
    Route::get('/statistik-pesanan/{id_user}', 'apiStatistikPesanan');
});


/*
|--------------------------------------------------------------------------
| Protected API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | User Routes
    |--------------------------------------------------------------------------
    */

    Route::prefix('user')
        ->controller(UserController::class)
        ->group(function () {
            Route::get('/{id_user}', 'detailUser');
            Route::post('/update-profile/{id_user}', 'editProfile');
            Route::get('/pemesanan/{id_user}', 'pemesananUser');
            Route::post('/tambah-alamat', 'tambahAlamatUser');
            Route::get('/list-alamat/{id_user}', 'listAlamatUser');
            Route::get('/detail-alamat/{id_alamat}', 'detailAlamatUser');
            Route::put('/update-alamat/{id_alamat}', 'updateAlamatUser');
            Route::delete('/delete-alamat/{id_alamat}', 'deleteAlamatUser');
            Route::put('/update-password/{id_user}', 'updatePasswordUser');
            Route::post('/tambah-bank', 'tambahBank');
            Route::post('/input-store/{id_user}', 'tambahStore');
            Route::post('/input-kyc/{id_user}', 'inputKYC');
        });

    Route::post('/logout', [UserController::class, 'logout']);


    /*
    |--------------------------------------------------------------------------
    | Product Routes
    |--------------------------------------------------------------------------
    */

    Route::prefix('produk')
        ->controller(ProductController::class)
        ->group(function () {
            Route::get('/produk-rating-tertinggi-limit6', 'produkRatingTertinggiLimit6');
            Route::get('/rekomendasi-pencarian', 'getRekomendasiPencarian');
            Route::get('/user-products', 'getUserProducts');
            Route::get('/detail-keranjang-produk/{parameter}', 'getDetailProdukKeranjang');
            Route::get('/detail-produk/{parameter}', 'getDetailProduct');
            Route::get('/{kategori?}', 'getProdukByFilter');
        });


    /*
    |--------------------------------------------------------------------------
    | Iklan Routes
    |--------------------------------------------------------------------------
    */

    Route::prefix('iklan')
        ->controller(IklanControlller::class)
        ->group(function () {
            Route::get('/', 'getAllIklan');
            Route::get('/{identifier}', 'getDetailIklan');
        });


    /*
    |--------------------------------------------------------------------------
    | Riwayat Pencarian Routes
    |--------------------------------------------------------------------------
    */

    Route::prefix('riwayat-pencarian')
        ->controller(RiwayatPencarianController::class)
        ->group(function () {
            Route::post('/insert/{id_user}', 'insert');
            Route::get('/show/{id_user}', 'show');
            Route::delete('/delete/{id_user}', 'delete');
        });


    /*
    |--------------------------------------------------------------------------
    | Transaksi Routes
    |--------------------------------------------------------------------------
    */

    Route::prefix('transaksi')
        ->controller(TransaksiController::class)
        ->group(function () {
            Route::post('/checkout/{id_user}', 'checkout');
            Route::post('/pembayaran', 'pembayaran');
            Route::get('/lokasi-toko', 'lokasiToko');
            Route::get('/bank-toko', 'bankToko');
        });


    /*
    |--------------------------------------------------------------------------
    | Riwayat Transaksi Routes
    |--------------------------------------------------------------------------
    */

    Route::prefix('riwayat')
        ->controller(TransaksiController::class)
        ->group(function () {
            Route::get('/', 'riwayat');
            Route::get('/rincian-produk', 'rincianProduk');
            Route::post('/bayar-sekarang', 'bayarSekarang');
            Route::post('/batalkan/{id_penyewaan}', 'batalkanPesanan');
        });

    Route::prefix('feedback')
        ->controller(FeedbackChatController::class)
        ->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'storeFeedback');
            Route::get('/{feedback}/messages', 'messages');
            Route::post('/{feedback}/messages', 'storeMessage');
        });
});
