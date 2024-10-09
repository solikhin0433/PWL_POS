<?php

use App\Http\Controllers\dashboardController;
use App\Http\Controllers\stokcontroller;
use App\Http\Controllers\kategoricontroller;
use App\Http\Controllers\barangcontroller;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\levelcontroller;
use App\Http\Controllers\usercontroller;
use App\Http\Controllers\welcomeController;
use App\Http\Controllers\AuthController;
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
Route::pattern('id', '[0-9]+'); // artinya ketika ada parameter (id), maka harus berupa angka

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');
Route::middleware('auth')->group(function () {
    // artinya semua route di dalam group ini harus login dulu
    Route::get('/',[welcomeController::class,'index']);

Route::group(['prefix'=>'user'], function(){
    Route::get('/', [usercontroller::class, 'index']); //menampilkan halaman awal user
    Route::post('/list',[usercontroller::class, 'list']); //menampilkan data user dalam bentuk json untuk data tables
    Route::get('/create',[usercontroller::class,'create']); //menampilkan halaman form tambah user
    Route::post('/',[usercontroller::class,'store']); //menyimpan data user baru
    Route::get('/create_ajax',[usercontroller::class, 'create_ajax']);//menampilkan halaman form tambah user ajax
    Route::post('/ajax',[usercontroller::class, 'store_ajax']);//meyimpan data user baru ajax
    Route::get('/{id}',[usercontroller::class,'show']); //menampilkan detail user
    Route::get('/{id}/edit',[usercontroller::class,'edit']); //menampilkan halaman form edit
    Route::put('/{id}',[usercontroller::class,'update']);//meyimpan perubahan data user
    Route::get('/{id}/edit_ajax',[usercontroller::class,'edit_ajax']); //menampilkan halaman form edit ajax
    Route::put('/{id}/update_ajax',[usercontroller::class,'update_ajax']);// update ajax
    Route::get('/{id}/delete_ajax',[usercontroller::class,'confirm_ajax']);
    Route::delete('/{id}/delete_ajax',[usercontroller::class,'delete_ajax']);
    Route::get('/{id}/show_ajax', [userController :: class, 'show_ajax']);
    Route::delete('/{id}',[usercontroller::class,'destroy']);//menghapus data user
   
});
Route::group(['prefix' => 'level', 'middleware'=>'authorize:ADM'], function () {
    
    Route::get('/', [LevelController::class, 'index']);         // menampilkan halaman awal level
    Route::post('/list', [LevelController::class, 'list']);     // menampilkan data level dalam bentuk json untuk datatables
    Route::get('/create', [LevelController::class, 'create']);  // menampilkan halaman form tambah level
    Route::post('/', [LevelController::class, 'store']);        // menyimpan data level baru
    Route::get('/create_ajax',[Levelcontroller::class, 'create_ajax']);//menampilkan halaman form tambah user ajax
    Route::get('/{id}/edit_ajax',[Levelcontroller::class,'edit_ajax']); 
    Route::put('/{id}/update_ajax',[Levelcontroller::class,'update_ajax']);
    Route::post('/ajax',[Levelcontroller::class, 'store_ajax']);//meyimpan data user baru ajax
    Route::get('/{id}', [LevelController::class, 'show']);      // menampilkan detail level
    Route::get('/{id}/edit', [LevelController::class, 'edit']); // menampilkan halaman form edit level
    Route::put('/{id}', [LevelController::class, 'update']);    // menyimpan perubahan data level
    Route::get('/{id}/delete_ajax',[Levelcontroller::class,'confirm_ajax']);
    Route::delete('/{id}/delete_ajax',[Levelcontroller::class,'delete_ajax']);
    Route::get('/{id}/show_ajax', [LevelController :: class, 'show_ajax']);
    Route::delete('/{id}', [LevelController::class, 'destroy']); // menghapus data level
});
Route::group(['prefix' => 'kategori', 'middleware'=>'authorize:ADM,MNG,STF'], function () {
    Route::get('/', [kategoricontroller::class, 'index']);         // menampilkan halaman awal kategori
    Route::post('/list', [kategoricontroller::class, 'list']);     // menampilkan data kategori dalam bentuk json untuk datatables
    Route::get('/create', [kategoricontroller::class, 'create']);  // menampilkan halaman form tambah kategori
    Route::post('/', [kategoricontroller::class,'store']);        // menyimpan data kategori baru
    Route::get('/create_ajax',[kategoricontroller::class, 'create_ajax']);//menampilkan halaman form tambah user ajax
    Route::post('/ajax',[kategoricontroller::class, 'store_ajax']);//meyimpan data user baru ajax
    Route::get('/{id}', [kategoricontroller::class,'show']);      // menampilkan detail kategori
    Route::get('/{id}/edit', [kategoricontroller::class, 'edit']); // menampilkan halaman form edit kategori
    Route::get('/{id}/edit_ajax',[kategoricontroller::class,'edit_ajax']); //menampilkan halaman form edit ajax
    Route::put('/{id}/update_ajax',[kategoricontroller::class,'update_ajax']);
    Route::put('/{id}', [kategoricontroller::class, 'update']);    // menyimpan perubahan data kategori
    Route::get('/{id}/delete_ajax',[kategoricontroller::class,'confirm_ajax']);
    Route::delete('/{id}/delete_ajax',[kategoricontroller::class,'delete_ajax']);
    Route::get('/{id}/show_ajax', [kategoriController :: class, 'show_ajax']);
    Route::delete('/{id}', [kategoricontroller::class, 'destroy']); // menghapus data kategori
});
Route::group(['prefix' => 'barang', 'middleware'=>'authorize:ADM,MNG,STF'], function () {
    Route::get('/', [barangcontroller::class, 'index']);         // menampilkan halaman awal barang
    Route::post('/list', [barangcontroller::class, 'list']);     // menampilkan data barang dalam bentuk json untuk datatables
    Route::get('/create', [barangcontroller::class, 'create']);  // menampilkan halaman form tambah barang
    Route::post('/', [barangcontroller::class,'store']);        // menyimpan data barang baru
    Route::get('/create_ajax',[barangcontroller::class, 'create_ajax']);//menampilkan halaman form tambah barang ajax
    Route::post('/ajax',[barangcontroller::class, 'store_ajax']);//meyimpan data barang baru ajax
    Route::get('/{id}/edit_ajax',[barangcontroller::class,'edit_ajax']); 
    Route::put('/{id}/update_ajax',[barangcontroller::class,'update_ajax']);
    Route::get('/{id}/delete_ajax',[barangcontroller::class,'confirm_ajax']);
    Route::delete('/{id}/delete_ajax',[barangcontroller::class,'delete_ajax']);
    Route::get('/{id}/show_ajax', [barangController :: class, 'show_ajax']);
    Route::get('/{id}', [barangcontroller::class,'show']);      // menampilkan detail barang
    Route::get('/{id}/edit', [barangcontroller::class, 'edit']); // menampilkan halaman form edit barang
    Route::put('/{id}', [barangcontroller::class, 'update']);    // menyimpan perubahan data barang
    Route::delete('/{id}', [barangcontroller::class, 'destroy']); // menghapus data barang
});
Route::group(['prefix' => 'supplier', 'middleware'=>'authorize:ADM,MNG,'], function () {
    Route::get('/', [suppliercontroller::class, 'index']);         // menampilkan halaman awal barang
    Route::post('/list', [suppliercontroller::class, 'list']);     // menampilkan data barang dalam bentuk json untuk datatables
    Route::get('/create', [suppliercontroller::class, 'create']);  // menampilkan halaman form tambah barang
    Route::post('/', [suppliercontroller::class,'store']);        // menyimpan data barang baru
    Route::get('/create_ajax',[suppliercontroller::class, 'create_ajax']);//menampilkan halaman form tambah supplier ajax
    Route::post('/ajax',[suppliercontroller::class, 'store_ajax']);//meyimpan data supplier baru ajax
    Route::get('/{id}/edit_ajax',[suppliercontroller::class,'edit_ajax']); 
    Route::put('/{id}/update_ajax',[suppliercontroller::class,'update_ajax']);
    Route::get('/{id}/delete_ajax',[suppliercontroller::class,'confirm_ajax']);
    Route::delete('/{id}/delete_ajax',[suppliercontroller::class,'delete_ajax']);
    Route::get('/{id}/show_ajax', [supplierController :: class, 'show_ajax']);
    Route::get('/{id}', [suppliercontroller::class,'show']);      // menampilkan detail barang
    Route::get('/{id}/edit', [suppliercontroller::class, 'edit']); // menampilkan halaman form edit barang
    Route::put('/{id}', [suppliercontroller::class, 'update']);    // menyimpan perubahan data barang
    Route::delete('/{id}', [suppliercontroller::class, 'destroy']); // menghapus data barang
});
Route::group(['prefix' => 'stok'], function () {
    Route::get('/', [stokcontroller::class, 'index']);          // Menampilkan halaman awal stok
    Route::post('/list', [stokcontroller::class, 'list']);      // Menampilkan data stok dalam bentuk JSON untuk DataTables
    Route::get('/create', [StokController::class, 'create']);   // Menampilkan halaman form tambah stok
    Route::post('/', [stokcontroller::class, 'store']);         // Menyimpan data stok baru
    Route::get('/{id}', [stokcontroller::class, 'show']);       // Menampilkan detail stok
    Route::get('/{id}/edit', [stokcontroller::class, 'edit']);  // Menampilkan halaman form edit stok
    Route::put('/{id}', [stokcontroller::class, 'update']);     // Menyimpan perubahan data stok
    Route::delete('/{id}', [stokcontroller::class, 'destroy']); // Menghapus data stok
});
});