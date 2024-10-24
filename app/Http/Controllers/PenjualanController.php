<?php

namespace App\Http\Controllers;

use App\Models\PenjualanModel;
use App\Models\BarangModel;
use App\Models\PenjualanDetailModel;
use App\Models\StokModel;
use App\Models\UserModel;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Yajra\DataTables\DataTables;

class PenjualanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Daftar Penjualan',
            'list' => ['Home', 'Penjualan']
        ];
        $page = (object)[
            'title' => 'Daftar penjualan yang terdaftar dalam sistem'
        ];
        $activeMenu = 'penjualan';
        $users = UserModel::all(); // Mengambil semua pengguna

        // Mengembalikan tampilan dengan data yang diperlukan
        return view('penjualan.index', compact('breadcrumb', 'page', 'activeMenu', 'users'));
    }

    public function list(Request $request)
{
    // Persiapkan kueri untuk data penjualan
    $penjualan = PenjualanModel::with(['user']) // Pastikan relasi user di-load
        ->select('penjualan_id', 'user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal');

    // Filter berdasarkan User ID
    if ($request->user_id) {
        $penjualan->where('user_id', $request->user_id); // Pastikan user_id ini benar
    }

    return DataTables::of($penjualan)
        ->addIndexColumn()
        ->addColumn('user_name', function($penjualan) {
            return $penjualan->user ? $penjualan->user->nama : 'N/A'; // Safeguard untuk null user
        })
        ->addColumn('aksi', function ($penjualan) {
            $btn = '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
            $btn .= '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
            return $btn; // Mengembalikan tombol aksi
        })
        ->rawColumns(['aksi'])
        ->make(true); // Mengembalikan data dalam format JSON
}



    public function create()
    {
        $breadcrumb = (object)[
            'title' => 'Tambah Penjualan',
            'list' => ['Home', 'Penjualan', 'Tambah']
        ];
        $page = (object)[
            'title' => 'Tambah penjualan baru'
        ];
        $users = UserModel::all(); // Mengambil semua pengguna
        $activeMenu = 'penjualan';

        return view('penjualan.create', compact('breadcrumb', 'page', 'activeMenu', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'pembeli' => 'required|string',
            'penjualan_kode' => 'required|string',
            'penjualan_tanggal' => 'required|date',
        ]);

        PenjualanModel::create([
            'user_id' => $request->user_id,
            'pembeli' => $request->pembeli,
            'penjualan_kode' => $request->penjualan_kode,
            'penjualan_tanggal' => $request->penjualan_tanggal,
        ]);

        return redirect('/penjualan')->with('success', 'Data penjualan berhasil disimpan');
    }


    public function destroy(string $penjualan_id)
    {
        $penjualan = PenjualanModel::find($penjualan_id);

        if (!$penjualan) {
            return redirect('/penjualan')->with('error', 'Data penjualan tidak ditemukan');
        }

        try {
            $penjualan->delete();
            return redirect('/penjualan')->with('success', 'Data penjualan berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/penjualan')->with('error', 'Gagal menghapus data penjualan terkait dengan data lain');
        }
    }
    public function show_ajax(string $id)
    {

        $penjualan = PenjualanModel::with(['user', 'penjualan_detail.barang'])->find($id);


        if ($penjualan) {

            return view('penjualan.show_ajax', [
                'penjualan' => $penjualan
            ]);
        } else {

            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }


    // AJAX Handlers
    public function create_ajax()
    {
        $penjualan = PenjualanModel::all();
        $user = UserModel::all();
        $barang = BarangModel::all();
        return view('penjualan.create_ajax', ['penjualan' => $penjualan, 'user' => $user, 'barang' => $barang]);
    }

    public function getHargaBarang($id)
    {
        // Ambil data barang berdasarkan id
        $barang = BarangModel::find($id);

        // Periksa apakah barang ditemukan
        if ($barang) {
            return response()->json([
                'status' => true,
                'harga_jual' => $barang->harga_jual
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Barang tidak ditemukan'
            ]);
        }
    }
    public function getStokBarang($barangId)
{
    // Fetch stock data based on barang ID
    $stok = StokModel::where('barang_id', $barangId)->first();
    
    if ($stok) {
        return response()->json([
            'status' => true,
            'stok_jumlah' => $stok->stok_jumlah
        ]);
    } else {
        return response()->json([
            'status' => false,
            'message' => 'Stok tidak ditemukan'
        ]);
    }
}

public function store_ajax(Request $request)
{
    $validator = Validator::make($request->all(), [
        'user_id' => 'required|numeric',
        'pembeli' => 'required|string|min:3|max:20',
        'penjualan_kode' => 'required|string|min:3|max:100|unique:t_penjualan,penjualan_kode',
        'penjualan_tanggal' => 'required|date',
        'barang_id' => 'required|array',
        'barang_id.*' => 'numeric',
        'harga' => 'required|array',
        'harga.*' => 'numeric',
        'jumlah' => 'required|array',
        'jumlah.*' => 'numeric',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'msgField' => $validator->errors()
        ]);
    }

    try {
        // Save the sale data
        $penjualan = new PenjualanModel();
        $penjualan->user_id = $request->user_id;
        $penjualan->pembeli = $request->pembeli;
        $penjualan->penjualan_kode = $request->penjualan_kode;
        $penjualan->penjualan_tanggal = $request->penjualan_tanggal;
        $penjualan->save();

        // Save item details and update stock
        foreach ($request->barang_id as $index => $barangId) {
            $detailPenjualan = new PenjualanDetailModel();
            $detailPenjualan->penjualan_id = $penjualan->penjualan_id;
            $detailPenjualan->barang_id = $barangId;
            $detailPenjualan->harga = $request->harga[$index];
            $detailPenjualan->jumlah = $request->jumlah[$index];
            $detailPenjualan->save();

            // Reduce stock
            $stok = StokModel::where('barang_id', $barangId)->first();
            if ($stok) {
                $stok->stok_jumlah -= $request->jumlah[$index];
                $stok->save();
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Data penjualan berhasil disimpan!'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Terjadi kesalahan, data gagal disimpan!',
            'error' => $e->getMessage()
        ]);
    }
}

    public function import()
    {
        return view('penjualan.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_penjualan' => ['required', 'mimes:xlsx', 'max:1024'] // Validasi file
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_penjualan'); // Mengambil file dari request
            $reader = IOFactory::createReader('Xlsx'); // Load Excel reader
            $reader->setReadDataOnly(true); // Hanya membaca data
            $spreadsheet = $reader->load($file->getRealPath()); // Load file Excel
            $sheet = $spreadsheet->getActiveSheet(); // Ambil sheet aktif

            $data = $sheet->toArray(null, false, true, true); // Ubah data sheet menjadi array

            $insertPenjualan = [];
            $insertDetailPenjualan = [];

            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if ($baris > 1) {
                        $penjualan_tanggal = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value['D'])->format('Y-m-d H:i:s');

                        // Proses data untuk tabel t_penjualan
                        $penjualan = PenjualanModel::updateOrCreate(
                            [
                                'penjualan_kode' => $value['C'] // Cek kode penjualan untuk menghindari duplikasi
                            ],
                            [
                                'user_id' => $value['A'],
                                'pembeli' => $value['B'],
                                'penjualan_kode' => $value['C'],
                                'penjualan_tanggal' => $penjualan_tanggal,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]
                        );

                        // Jika penjualan berhasil diupdate/insert, proses detail penjualan
                        $insertDetailPenjualan[] = [
                            'penjualan_id' => $penjualan->penjualan_id, // Relasi ke penjualan_id di tabel t_penjualan
                            'barang_id' => $value['E'],
                            'harga' => $value['F'],
                            'jumlah' => $value['G'],
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }
                }

                // Insert detail penjualan
                if (count($insertDetailPenjualan) > 0) {
                    PenjualanDetailModel::insertOrIgnore($insertDetailPenjualan);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimport'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }
        }
        return redirect('/');
    }
    public function confirm_ajax(string $penjualan_id)
    {
        // Mencari data penjualan berdasarkan ID
        $penjualan = PenjualanModel::find($penjualan_id);
    
        // Jika data penjualan ditemukan, ambil detail penjualan
        if ($penjualan) {
            // Ambil detail barang terkait penjualan
            $penjualanDetail = PenjualanDetailModel::where('penjualan_id', $penjualan_id)->get();
    
            // Kirim data ke view 'penjualan.confirm_ajax'
            return view('penjualan.confirm_ajax', [
                'penjualan' => $penjualan,
                'penjualanDetail' => $penjualanDetail // Kirim juga detail barang
            ]);
        } else {
            // Jika penjualan tidak ditemukan, kembalikan response JSON untuk kesalahan
            return response()->json([
                'status' => false,
                'message' => 'Data penjualan tidak ditemukan.'
            ], 404);
        }
    }
    
    public function delete_ajax(Request $request, $penjualan_id)
    {
        // Cek apakah request datang dari AJAX atau menginginkan respons JSON
        if ($request->ajax() || $request->wantsJson()) {
            // Cari data penjualan berdasarkan ID
            $penjualan = PenjualanModel::find($penjualan_id);
    
            // Jika penjualan ditemukan
            if ($penjualan) {
                try {
                    // Lakukan penghapusan dalam sebuah transaksi untuk konsistensi data
                    DB::transaction(function () use ($penjualan) {
                        // Hapus semua detail penjualan terkait
                        PenjualanDetailModel::where('penjualan_id', $penjualan->penjualan_id)->delete();
    
                        // Hapus data penjualan
                        $penjualan->delete();
                    });
    
                    // Return success response
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil dihapus'
                    ]);
                } catch (\Illuminate\Database\QueryException $e) {
                    // Jika ada error dalam penghapusan
                    return response()->json([
                        'status' => false,
                        'message' => 'Data gagal dihapus karena terdapat relasi dengan tabel lain'
                    ]);
                }
            } else {
                // Jika penjualan tidak ditemukan
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
    
        // Jika bukan request AJAX, redirect ke halaman utama
        return redirect('/');
    }
    
    public function export_excel()
    {
        $penjualan = PenjualanModel::with('penjualan_detail')->get(); // Ganti 'detail' dengan 'penjualan_detail'
    
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        // Set Header Kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'User ID');
        $sheet->setCellValue('C1', 'Pembeli');
        $sheet->setCellValue('D1', 'Kode Penjualan');
        $sheet->setCellValue('E1', 'Tanggal Penjualan');
        $sheet->setCellValue('F1', 'Barang ID');
        $sheet->setCellValue('G1', 'Harga');
        $sheet->setCellValue('H1', 'Jumlah');
    
        // Buat header menjadi bold
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
    
        $no = 1; // Nomor data dimulai dari 1
        $baris = 2; // Baris data dimulai dari baris ke-2
    
        foreach ($penjualan as $penjualanItem) {
            foreach ($penjualanItem->penjualan_detail as $detail) { // Ganti 'detail' dengan 'penjualan_detail'
                $sheet->setCellValue('A' . $baris, $no);
                $sheet->setCellValue('B' . $baris, $penjualanItem->user_id);
                $sheet->setCellValue('C' . $baris, $penjualanItem->pembeli);
                $sheet->setCellValue('D' . $baris, $penjualanItem->penjualan_kode);
                $sheet->setCellValue('E' . $baris, $penjualanItem->penjualan_tanggal);
                $sheet->setCellValue('F' . $baris, $detail->barang_id);
                $sheet->setCellValue('G' . $baris, $detail->harga);
                $sheet->setCellValue('H' . $baris, $detail->jumlah);
    
                $baris++;
                $no++;
            }
        }
    
        // Set ukuran kolom otomatis untuk semua kolom
        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    
        // Set judul sheet
        $sheet->setTitle('Data Penjualan');
    
        // Buat writer
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Penjualan ' . date('Y-m-d H:i:s') . '.xlsx';
    
        // Atur Header untuk Download File Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
    
        // Simpan file dan kirim ke output
        $writer->save('php://output');
        exit;
    }
    public function export_pdf()
{
    // Ambil data penjualan dari database
    $penjualan = PenjualanModel::with('penjualan_detail') // Ganti 'detail' dengan 'penjualan_detail'
        ->orderBy('penjualan_tanggal')
        ->get();

    // Gunakan library Dompdf untuk membuat PDF
    $pdf = PDF::loadView('penjualan.export_pdf', ['penjualan' => $penjualan]);

    // Atur ukuran kertas dan orientasi
    $pdf->setPaper('A4', 'portrait');

    // Aktifkan opsi untuk memuat gambar dari URL (jika ada)
    $pdf->setOption('isRemoteEnabled', true);

    // Render PDF
    return $pdf->stream('Data Penjualan ' . date('Y-m-d H:i:s') . '.pdf');
}
}