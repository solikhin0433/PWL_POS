<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangModel;
use App\Models\KategoriModel;
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
                // Mengambil nama kategori dari relasi
                return $barang->kategori ? $barang->kategori->kategori_nama : '-';
            })
            ->addColumn('aksi', function ($barang) { 
                // Menambahkan kolom aksi (edit, delete)
                $btn  = '<a href="' . url('/barang/' . $barang->barang_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url('/barang/' . $barang->barang_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/barang/' . $barang->barang_id) . '">'
                    . csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi']) // Memberitahu bahwa kolom aksi berisi HTML
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
}