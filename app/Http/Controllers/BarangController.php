<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\BarangModel;
use App\Models\KategoriModel;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;


class BarangController extends Controller
{
    // Menampilkan halaman awal barang
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Barang',
            'list' => ['Home', 'Barang']
        ];

        $page = (object) [
            'title' => 'Daftar barang yang terdaftar dalam sistem'
        ];

        $activeMenu = 'barang'; // Set menu yang sedang aktif

        $kategori = KategoriModel::all(); // Ambil data kategori untuk filter kategori

        return view('barang.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'kategori' => $kategori,
            'activeMenu' => $activeMenu
        ]);
    }

    // Mengambil data barang dalam bentuk JSON untuk DataTables
    public function list(Request $request)
    {
        $barang = BarangModel::select('barang_id', 'kategori_id', 'barang_kode', 'barang_nama','harga_beli','harga_jual')
            ->with('kategori');

        // Filter berdasarkan kategori jika ada
        if ($request->kategori_id) {
            $barang->where('kategori_id', $request->kategori_id);
        }

        return DataTables::of($barang)
        // Menambahkan kolom index (DT_RowIndex)
        ->addIndexColumn()
        ->addColumn('kategori_nama', function ($barang) {
            return $barang->kategori ? $barang->kategori->kategori_nama : '-'; // Assuming 'kategori_nama' is the column name
        })
        ->addColumn('aksi', function ($barang) {
            $btn  = '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
            $btn .= '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
            $btn .= '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
            return $btn;
        })
        ->rawColumns(['aksi'])
        ->make(true);
    
    }
    // Menampilkan halaman form tambah user
    public function create(){     
         $breadcrumb = (object) [
            'title' => 'Tambah Barang',
            'list' => ['Home', 'Barang', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah barang baru'
        ];

        $activeMenu = 'barang'; // Set menu yang sedang aktif

        $kategori = KategoriModel::all(); // Ambil data kategori

        return view('barang.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'kategori' => $kategori,
            'activeMenu' => $activeMenu
        ]);
    }
    public function store(Request $request){
        $request->validate([
            'kategori_id'=>'required|integer',
            'barang_kode'=>'required|string|min:3|unique:m_barang,barang_kode',
            'barang_nama'=>'required|string|max:100',
            'harga_beli'=>'required|integer',
            'harga_jual'=>'required|integer',
        ]);
        barangmodel::create([
            'kategori_id'=>$request->kategori_id,
            'barang_kode'=>$request->barang_kode,
            'barang_nama'=>$request->barang_nama,
            'harga_beli'=>$request->harga_beli,
            'harga_jual'=>$request->harga_jual,
            
        ]);

        return redirect('/barang',)->with('success','Data barang berhasil disimpan');
    }
     // create ajax
     public function create_ajax(){
        $kategori = KategoriModel::all();
        return view('barang.create_ajax', ['kategori' => $kategori]);
    }
    
    // store ajax
    public function store_ajax(Request $request)
    {
        // Cek apakah request berupa Ajax
        if ($request->ajax() || $request->wantsJson()) {
            // Aturan validasi
            $rules = [
                'kategori_id' => 'required|integer',
                'barang_kode' => 'required|string|min:2|unique:m_barang,barang_kode',
                'barang_nama' => 'required|string|max:100',
                'harga_beli'  => 'required|numeric|min:1',
                'harga_jual'  => 'required|numeric|min:1'
            ];

            // Validasi data inputan
            $validator = Validator::make($request->all(), $rules);

            // Jika validasi gagal
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors() // Pesan error validasi
                ]);
            }

            // Simpan data ke database
            BarangModel::create($request->all());

            // Kembalikan respon JSON jika berhasil
            return response()->json([
                'status' => true,
                'message' => 'Data barang berhasil disimpan'
            ]);
        }

        // Jika bukan request Ajax, redirect ke halaman lain (misalnya halaman utama)
        return redirect('/');
    }

    // Menampilkan detail barang
    public function show($id){
        $breadcrumb = (object) [
            'title' => 'Detail Barang',
            'list' => ['Home', 'Barang', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail barang'
        ];

        $activeMenu = 'barang'; // Set menu yang sedang aktif

        $barang = BarangModel::find($id);

        return view('barang.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'barang' => $barang,
            'activeMenu' => $activeMenu
        ]);
    }
    // edit ajax
    public function edit_ajax(string $id)
    {
        $barang = BarangModel::find($id);
        $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();

        return view('barang.edit_ajax', ['barang' => $barang, 'kategori' => $kategori]);
    }

    // Menampilkan halaman form edit barang
    public function edit(string $barang_id){
        $barang = BarangModel::find($barang_id);
        $kategori = KategoriModel::all();

        $breadcrumb = (object)[
            'title' =>'Edit data barang',
            'list' =>['Home','data barang','edit']
        ];
        $page = (object)[
            'title'=>'Edit data barang'
        ];
        $activeMenu = 'barang';
        return view('barang.edit',['breadcrumb'=>$breadcrumb,'page'=>$page,'barang'=>$barang,'kategori'=>$kategori, 'activeMenu'=>$activeMenu]);
    }
    // Mengupdate data barang
    public function update(Request $request, string $barang_id){
        $request->validate([
            'kategori_id' => 'required|integer',
            'barang_kode' => 'required|string|min:3|unique:m_barang,barang_kode,' . $barang_id . ',barang_id',
            'barang_nama' => 'required|string|max:100',
            'harga_beli' => 'required|integer',
            'harga_jual' => 'required|integer',
           
        ]);
        

        BarangModel::where('barang_id', $barang_id)->update([
            'kategori_id'=>$request->kategori_id,
            'barang_kode'=>$request->barang_kode,
            'barang_nama'=>$request->barang_nama,
            'harga_beli'=>$request->harga_beli,
            'harga_jual'=>$request->harga_jual,
            
        ]);

        return redirect('/barang')->with('success','Data barang berhasil diupdate');
    }
     // Update data barang menggunakan Ajax
    public function update_ajax(Request $request, $id)
    {
        // Cek apakah request dari Ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_id' => 'required|integer',
                'barang_kode' => 'required|max:5|unique:m_barang,barang_kode,' . $id . ',barang_id',
                'barang_nama' => 'required|max:100',
                'harga_beli'  => 'required|numeric|min:1',
                'harga_jual'  => 'required|numeric|min:1'
            ];

            $validator = Validator::make($request->all(), $rules);

            // Jika validasi gagal
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $barang = BarangModel::find($id);
            if ($barang) {
                $barang->update($request->all());

                return response()->json([
                    'status' => true,
                    'message' => 'Data barang berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data barang tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }
      // Menampilkan konfirmasi hapus barang menggunakan Ajax
      public function confirm_ajax(string $id)
      {
          $barang = BarangModel::find($id);
          return view('barang.confirm_ajax', ['barang' => $barang]);
      }
     // delete ajax
     public function delete_ajax(Request $request, $barang_id)
{
    if ($request->ajax() || $request->wantsJson()) {
        $barang = barangmodel::find($barang_id);
        
        if ($barang) {
            try {
                $barang->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini'
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    return redirect('/');
}
    // Menghapus data barang
    public function destroy(string $barang_id){
        $check = barangmodel::find($barang_id);
        if(!$check){
            return redirect('/barang')->with('error','Data user tidak ditemukan');
        }

        try{
            barangmodel::destroy($barang_id);
            return redirect('/barang')->with('success', 'Data barang berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e){
            return redirect('/barang')->with('error','Data barang gagal dhapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
    // show ajax
     public function show_ajax(string $id) {
        // Cari barang berdasarkan id
        $barang = BarangModel::find($id);
    
        // Periksa apakah barang ditemukan
        if ($barang) {
            // Tampilkan halaman show_ajax dengan data barang
            return view('barang.show_ajax', ['barang' => $barang]);
        } else {
            // Tampilkan pesan kesalahan jika barang tidak ditemukan
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }
}