<?php

use App\Http\Controllers\dashboardController;
use App\Http\Controllers\stokcontroller;
use App\Http\Controllers\penjualancontroller;
use App\Http\Controllers\kategoricontroller;
use App\Http\Controllers\barangcontroller;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\levelcontroller;
use App\Http\Controllers\usercontroller;
use App\Http\Controllers\welcomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
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

Route::pattern ('id', '[0-9]+'); // artinya ketika ada parameter (id), maka harus berupa angka

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');
Route::get('register', [RegisterController::class, 'register'])->name('register');
Route::post('register', [RegisterController::class, 'store']);


Route::middleware('auth')->group(function () {
    // artinya semua route di dalam group ini harus login dulu
    Route::get('/', [welcomeController::class, 'index']);

    Route::group(['prefix'=>'profile'], function(){
    Route::get('/edit', [UserController::class, 'profile']);
    Route::post('/update_profile', [UserController::class, 'update_profile']);
    Route::put('/update', [UserController::class, 'updateinfo']);
    Route::put('/update_password', [UserController::class, 'update_password']);
    Route::post('/delete_avatar', [UserController::class, 'deleteAvatar']);
    });
    

    Route::group(['prefix' => 'user', 'middleware' => 'authorize:ADM'], function () {
        Route::get('/', [usercontroller::class, 'index']); //menampilkan halaman awal user
        Route::post('/list', [usercontroller::class, 'list']); //menampilkan data user dalam bentuk json untuk data tables
        Route::get('/create', [usercontroller::class, 'create']); //menampilkan halaman form tambah user
        Route::post('/', [usercontroller::class, 'store']); //menyimpan data user baru
        Route::get('/create_ajax', [usercontroller::class, 'create_ajax']); //menampilkan halaman form tambah user ajax
        Route::post('/ajax', [usercontroller::class, 'store_ajax']); //meyimpan data user baru ajax
        Route::get('/{id}', [usercontroller::class, 'show']); //menampilkan detail user
        Route::get('/{id}/edit', [usercontroller::class, 'edit']); //menampilkan halaman form edit
        Route::put('/{id}', [usercontroller::class, 'update']); //meyimpan perubahan data user
        Route::get('/{id}/edit_ajax', [usercontroller::class, 'edit_ajax']); //menampilkan halaman form edit ajax
        Route::put('/{id}/update_ajax', [usercontroller::class, 'update_ajax']); // update ajax
        Route::get('/{id}/delete_ajax', [usercontroller::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [usercontroller::class, 'delete_ajax']);
        Route::get('/{id}/show_ajax', [userController::class, 'show_ajax']);
        Route::delete('/{id}', [usercontroller::class, 'destroy']); //menghapus data user
        Route::get('/import', [userController::class, 'import']); // ajax form upload excel
        Route::post('/import_ajax', [userController::class, 'import_ajax']); // ajax import excel
        Route::get('/export_excel', [UserController::class, 'export_excel']); // ajax exsport excel
        Route::get('/export_pdf', [UserController::class, 'export_pdf']);// export pdf


    });
    Route::group(['prefix' => 'level', 'middleware' => 'authorize:ADM'], function () {
        Route::get('/', [LevelController::class, 'index']);         // menampilkan halaman awal level
        Route::post('/list', [LevelController::class, 'list']);     // menampilkan data level dalam bentuk json untuk datatables
        Route::get('/create', [LevelController::class, 'create']);  // menampilkan halaman form tambah level
        Route::post('/', [LevelController::class, 'store']);        // menyimpan data level baru
        Route::get('/create_ajax', [Levelcontroller::class, 'create_ajax']); //menampilkan halaman form tambah user ajax
        Route::get('/{id}/edit_ajax', [Levelcontroller::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [Levelcontroller::class, 'update_ajax']);
        Route::post('/ajax', [Levelcontroller::class, 'store_ajax']); //meyimpan data user baru ajax
        Route::get('/{id}', [LevelController::class, 'show']);      // menampilkan detail level
        Route::get('/{id}/edit', [LevelController::class, 'edit']); // menampilkan halaman form edit level
        Route::put('/{id}', [LevelController::class, 'update']);    // menyimpan perubahan data level
        Route::get('/{id}/delete_ajax', [Levelcontroller::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [Levelcontroller::class, 'delete_ajax']);
        Route::get('/{id}/show_ajax', [LevelController::class, 'show_ajax']);
        Route::delete('/{id}', [LevelController::class, 'destroy']); // menghapus data level
        Route::get('/import', [LevelController::class, 'import']); // ajax form upload excel
        Route::post('/import_ajax', [LevelController::class, 'import_ajax']); // ajax import excel
        Route::get('/export_excel', [LevelController::class, 'export_excel']); // ajax exsport excel
        Route::get('/export_pdf', [LevelController::class, 'export_pdf']);// export pdf
    });
    Route::group(['prefix' => 'kategori', 'middleware' => 'authorize:ADM,MNG,STF'], function () {
        Route::get('/', [kategoricontroller::class, 'index']);         // menampilkan halaman awal kategori
        Route::post('/list', [kategoricontroller::class, 'list']);     // menampilkan data kategori dalam bentuk json untuk datatables
        Route::get('/create', [kategoricontroller::class, 'create']);  // menampilkan halaman form tambah kategori
        Route::post('/', [kategoricontroller::class, 'store']);        // menyimpan data kategori baru
        Route::get('/create_ajax', [kategoricontroller::class, 'create_ajax']); //menampilkan halaman form tambah user ajax
        Route::post('/ajax', [kategoricontroller::class, 'store_ajax']); //meyimpan data user baru ajax
        Route::get('/{id}', [kategoricontroller::class, 'show']);      // menampilkan detail kategori
        Route::get('/{id}/edit', [kategoricontroller::class, 'edit']); // menampilkan halaman form edit kategori
        Route::get('/{id}/edit_ajax', [kategoricontroller::class, 'edit_ajax']); //menampilkan halaman form edit ajax
        Route::put('/{id}/update_ajax', [kategoricontroller::class, 'update_ajax']);
        Route::put('/{id}', [kategoricontroller::class, 'update']);    // menyimpan perubahan data kategori
        Route::get('/{id}/delete_ajax', [kategoricontroller::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [kategoricontroller::class, 'delete_ajax']);
        Route::get('/{id}/show_ajax', [kategoriController::class, 'show_ajax']);
        Route::delete('/{id}', [kategoricontroller::class, 'destroy']); // menghapus data kategori
        Route::get('/import', [kategoricontroller::class, 'import']); // ajax form upload excel
        Route::post('/import_ajax', [kategoricontroller::class, 'import_ajax']); // ajax import excel
        Route::get('/export_excel', [kategoriController::class, 'export_excel']); // ajax import excel
        Route::get('/export_pdf', [kategoriController::class, 'export_pdf']);// export pdf
    });
    Route::group(['prefix' => 'barang', 'middleware' => 'authorize:ADM,MNG,STF'], function () {
        Route::get('/', [barangcontroller::class, 'index']);         // menampilkan halaman awal barang
        Route::post('/list', [barangcontroller::class, 'list']);     // menampilkan data barang dalam bentuk json untuk datatables
        Route::get('/create', [barangcontroller::class, 'create']);  // menampilkan halaman form tambah barang
        Route::post('/', [barangcontroller::class, 'store']);        // menyimpan data barang baru
        Route::get('/create_ajax', [barangcontroller::class, 'create_ajax']); //menampilkan halaman form tambah barang ajax
        Route::post('/ajax', [barangcontroller::class, 'store_ajax']); //meyimpan data barang baru ajax
        Route::get('/{id}/edit_ajax', [barangcontroller::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [barangcontroller::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [barangcontroller::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [barangcontroller::class, 'delete_ajax']);
        Route::get('/{id}/show_ajax', [barangController::class, 'show_ajax']);
        Route::get('/{id}', [barangcontroller::class, 'show']);      // menampilkan detail barang
        Route::get('/{id}/edit', [barangcontroller::class, 'edit']); // menampilkan halaman form edit barang
        Route::put('/{id}', [barangcontroller::class, 'update']);    // menyimpan perubahan data barang
        Route::delete('/{id}', [barangcontroller::class, 'destroy']); // menghapus data barang
        Route::get('/import', [BarangController::class, 'import']); // ajax form upload excel
        Route::post('/import_ajax', [BarangController::class, 'import_ajax']); // ajax import excel
        Route::get('/export_excel', [BarangController::class, 'export_excel']); // ajax import excel
        Route::get('/export_pdf', [BarangController::class, 'export_pdf']);// export pdf
        
    });
    Route::group(['prefix' => 'supplier', 'middleware' => 'authorize:ADM,MNG,'], function () {
        Route::get('/', [suppliercontroller::class, 'index']);         // menampilkan halaman awal barang
        Route::post('/list', [suppliercontroller::class, 'list']);     // menampilkan data barang dalam bentuk json untuk datatables
        Route::get('/create', [suppliercontroller::class, 'create']);  // menampilkan halaman form tambah barang
        Route::post('/', [suppliercontroller::class, 'store']);        // menyimpan data barang baru
        Route::get('/create_ajax', [suppliercontroller::class, 'create_ajax']); //menampilkan halaman form tambah supplier ajax
        Route::post('/ajax', [suppliercontroller::class, 'store_ajax']); //meyimpan data supplier baru ajax
        Route::get('/{id}/edit_ajax', [suppliercontroller::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [suppliercontroller::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [suppliercontroller::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [suppliercontroller::class, 'delete_ajax']);
        Route::get('/{id}/show_ajax', [supplierController::class, 'show_ajax']);
        Route::get('/{id}', [suppliercontroller::class, 'show']);      // menampilkan detail barang
        Route::get('/{id}/edit', [suppliercontroller::class, 'edit']); // menampilkan halaman form edit barang
        Route::put('/{id}', [suppliercontroller::class, 'update']);    // menyimpan perubahan data barang
        Route::delete('/{id}', [suppliercontroller::class, 'destroy']); // menghapus data barang
        Route::get('/import', [suppliercontroller::class, 'import']); // ajax form upload excel
        Route::post('/import_ajax', [suppliercontroller::class, 'import_ajax']); // ajax import excel
        Route::get('/export_excel', [supplierController::class, 'export_excel']); // ajax import excel
        Route::get('/export_pdf', [supplierController::class, 'export_pdf']);// export pdf
    });
    Route::group(['prefix' => 'stok',  'middleware' => 'authorize:ADM'], function () {
        Route::get('/', [StokController::class, 'index']);          // Menampilkan halaman awal stok
        Route::post('/list', [StokController::class, 'list']);      // Menampilkan data stok dalam bentuk JSON untuk DataTables
        Route::get('/create', [StokController::class, 'create']);   // Menampilkan halaman form tambah stok
        Route::post('/', [StokController::class, 'store']);         // Menyimpan data stok baru
        Route::get('/create_ajax', [StokController::class, 'create_ajax']); // menampilkan halaman form tambah supplier ajax
        Route::post('/ajax', [StokController::class, 'store_ajax']); // menyimpan data supplier baru ajax
        Route::get('/{id}/edit_ajax', [StokController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [StokController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [StokController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [StokController::class, 'delete_ajax']);
        Route::get('/{id}/show_ajax', [StokController::class, 'show_ajax']);
        Route::get('/{id}', [StokController::class, 'show']);       // Menampilkan detail stok
        Route::get('/{id}/edit', [StokController::class, 'edit']);  // Menampilkan halaman form edit stok
        Route::put('/{id}', [StokController::class, 'update']);     // Menyimpan perubahan data stok
        Route::delete('/{id}', [StokController::class, 'destroy']); // Menghapus data stok
        Route::get('/import', [StokController::class, 'import']); // ajax form upload excel
        Route::post('/import_ajax', [StokController::class, 'import_ajax']); // ajax import excel
        Route::get('/export_excel', [StokController::class, 'export_excel']); // ajax import excel
        Route::get('/export_pdf', [StokController::class, 'export_pdf']); // export pdf
    });
    Route::group(['prefix' => 'penjualan', 'middleware' => 'authorize:ADM'], function () {
        Route::get('/', [PenjualanController::class, 'index']);          // Menampilkan halaman awal penjualan
        Route::post('/list', [PenjualanController::class, 'list']);      // Menampilkan data penjualan dalam bentuk JSON untuk DataTables
        Route::get('/create', [PenjualanController::class, 'create']);   // Menampilkan halaman form tambah penjualan
        Route::post('/', [PenjualanController::class, 'store']);         // Menyimpan data penjualan baru
        Route::get('/create_ajax', [PenjualanController::class, 'create_ajax']); // Menampilkan halaman form tambah penjualan ajax
        Route::post('/ajax', [PenjualanController::class, 'store_ajax']); // Menyimpan data penjualan baru ajax
        Route::put('/{id}/update_ajax', [PenjualanController::class, 'update_ajax']); // Menyimpan perubahan data penjualan ajax
        Route::get('/{id}/delete_ajax', [PenjualanController::class, 'confirm_ajax']); // Konfirmasi hapus data penjualan ajax
        Route::delete('/{id}/delete_ajax', [PenjualanController::class, 'delete_ajax']); // Menghapus data penjualan via ajax
        Route::get('/{id}/show_ajax', [PenjualanController::class, 'show_ajax']); // Menampilkan detail penjualan ajax
        Route::get('/{id}', [PenjualanController::class, 'show']);       // Menampilkan detail penjualan
        Route::put('/{id}', [PenjualanController::class, 'update']);     // Menyimpan perubahan data penjualan
        Route::delete('/{id}', [PenjualanController::class, 'destroy']); // Menghapus data penjualan
        Route::get('/import', [penjualancontroller::class, 'import']); // ajax form upload excel
        Route::post('/import_ajax', [PenjualanController::class, 'import_ajax']); // ajax import excel
        Route::get('/export_excel', [PenjualanController::class, 'export_excel']); // Export data penjualan ke Excel
        Route::get('/export_pdf', [PenjualanController::class, 'export_pdf']);     // Export data penjualan ke PD
        Route::get('/harga-barang/{id}', [PenjualanController::class, 'getHargaBarang']);
        Route::get('/getStokBarang/{barangId}', [PenjualanController::class, 'getStokBarang']);

        
    });
    
    
});