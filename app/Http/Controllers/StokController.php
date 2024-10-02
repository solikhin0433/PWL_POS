<?php

namespace App\Http\Controllers;

use App\Models\StokModel;
use App\Models\UserModel;
use App\Models\SupplierModel;
use App\Models\BarangModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StokController extends Controller
{
    // Menampilkan halaman awal stok
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Stok',
            'list' => ['Home', 'Stok']
        ];

        $page = (object) [
            'title' => 'Daftar stok yang tersedia dalam sistem'
        ];

        $user = UserModel::all();
        $supplier = SupplierModel::all();
        $barang = BarangModel::all();
        $activeMenu = 'stok'; // set menu yang sedang aktif

        return view('stok.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'user' => $user,
            'supplier' => $supplier,
            'barang' => $barang,
            'activeMenu' => $activeMenu
        ]);
    }

    // Ambil data stok dalam bentuk json untuk datatables 
    public function list(Request $request)
    {
        $stok = StokModel::with(['user', 'supplier', 'barang']);

        // Filter berdasarkan user, barang, dan supplier
        if ($request->user_id) {
            $stok->where('user_id', $request->user_id);
        }
        if ($request->barang_id) {
            $stok->where('barang_id', $request->barang_id);
        }
        if ($request->suppllier_id) { // Ensure spelling is correct
            $stok->where('suppllier_id', $request->suppllier_id);
        }

        return DataTables::of($stok)
            ->addIndexColumn()
            ->addColumn('aksi', function ($stok) {
                $btn = '<a href="' . url('/stok/' . $stok->stok_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url('/stok/' . $stok->stok_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/stok/' . $stok->stok_id) . '">'
                    . csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // Menampilkan halaman form tambah stok
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Stok',
            'list' => ['Home', 'Stok', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah stok baru'
        ];

        $user = UserModel::all();
        $supplier = SupplierModel::all();
        $barang = BarangModel::all();
        $activeMenu = 'stok';

        return view('stok.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'user' => $user,
            'supplier' => $supplier,
            'barang' => $barang,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menyimpan data stok
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'suppllier_id' => 'required', // Ensure spelling is correct
            'barang_id' => 'required',
            'stok_jumlah' => 'required|numeric',
            'stok_tanggal' => 'required|date',
        ]);

        // Ambil user_id dari autentikasi
        $userId = auth()->id();

        // Pastikan user_id tidak null
        if (!$userId) {
            return redirect()->back()->withErrors(['user_id' => 'User must be authenticated.']);
        }

        StokModel::create([
            'suppllier_id' => $request->suppllier_id, // Ensure spelling is correct
            'barang_id' => $request->barang_id,
            'stok_jumlah' => $request->stok_jumlah,
            'stok_tanggal' => $request->stok_tanggal,
            'user_id' => $userId, // Menyimpan ID user yang sedang login
        ]);

        // Jika berhasil, kembalikan pesan sukses
        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    // Menampilkan detail stok
    public function show(string $id)
    {
        $stok = StokModel::with(['user', 'supplier', 'barang'])->find($id);

        $breadcrumb = (object) [
            'title' => 'Detail Stok',
            'list' => ['Home', 'Stok', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail stok'
        ];

        $activeMenu = 'stok';

        return view('stok.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'stok' => $stok,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menampilkan halaman form edit stok
    public function edit(string $id)
    {
        $stok = StokModel::find($id);
        $user = UserModel::all();
        $supplier = SupplierModel::all();
        $barang = BarangModel::all();

        $breadcrumb = (object) [
            'title' => 'Edit Stok',
            'list' => ['Home', 'Stok', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit stok'
        ];

        $activeMenu = 'stok';

        return view('stok.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'stok' => $stok,
            'user' => $user,
            'supplier' => $supplier,
            'barang' => $barang,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menyimpan perubahan data stok
    public function update(Request $request, string $id)
    {
        // Validate incoming request
        $request->validate([
            'suppllier_id' => 'required|integer',
            'barang_id' => 'required|integer',
            'stok_tanggal' => 'required|date',
            'stok_jumlah' => 'required|integer'
        ]);
    
        // Update the stock record
        StokModel::find($id)->update([
            'suppllier_id' => $request->suppllier_id,
            'barang_id' => $request->barang_id,
            'user_id' => auth()->id(), // Store the ID of the currently authenticated user
            'stok_tanggal' => $request->stok_tanggal,
            'stok_jumlah' => $request->stok_jumlah
        ]);
    
        return redirect('/stok')->with('success', 'Data stok berhasil diubah');
    }
    
    // Menghapus data stok
    public function destroy(string $id)
    {
        $stok = StokModel::find($id);

        if (!$stok) {
            return redirect('/stok')->with('error', 'Data stok tidak ditemukan');
        }

        try {
            StokModel::destroy($id);

            return redirect('/stok')->with('success', 'Data stok berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/stok')->with('error', 'Data stok gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}