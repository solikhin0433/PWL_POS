<?php

namespace App\Http\Controllers;

use App\Models\KategoriModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class KategoriController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Kategori',
            'list' => ['Home', 'Kategori']
        ];

        $page = (object) [
            'title' => 'Daftar kategori barang yang terdaftar dalam sistem'
        ];

        $activeMenu = 'kategori';

        return response()->view('kategori.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }
    // Ambil data user dalam bentuk json untuk datables
    public function list(Request $request)
    {
        $kategori = KategoriModel::select('kategori_id', 'kategori_kode', 'kategori_nama',);

        if ($request->kategori_id){
            $kategori->where('kategori_id',$request->kategori_id);
        }
        return DataTables::of($kategori)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kategori) { 
                $btn  = '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id .
                    '/delete_ajax') . '\')"  class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }


    // Tampilkan form input kategori baru
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Kategori',
            'list' => ['Home', 'Kategori', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah kategori baru'
        ];

        $activeMenu = 'kategori';

        return response()->view('kategori.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menyimpan data level ke dalam database
    public function store(Request $request)
    {
        // Validasi input data dari form
        $request->validate([
            'kategori_kode' => 'required|string|min:3|unique:m_kategori,kategori_kode', // level_kode harus diisi, berupa string, minimal 3 karakter, dan bernilai unik di tabel levels
            'kategori_nama' => 'required|string|max:100', // level_nama harus diisi, berupa string, dan maksimal 100 karakter
        ]);

        // Menyimpan data level ke dalam database
        KategoriModel::create([
            'kategori_kode' => $request->kategori_kode,
            'kategori_nama' => $request->kategori_nama,
        ]);

        // Redirect ke halaman daftar level dengan pesan sukses
        return redirect('/kategori')->with('success', 'Data kategor berhasil disimpan');
    }
    // Menampilkan detail kategori
    public function show(string $id)
    {
        // Ambil data kategori berdasarkan id
        $kategori = KategoriModel::find($id);

        $breadcrumb = (object) [
            'title' => 'Detail Kategori',
            'list' => ['Home', 'kategori', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail kategori'
        ];

        $activeMenu = 'kategori'; // Set menu yang sedang aktif

        // Pastikan variabel 'kategori' digunakan di sini karena itulah yang dikirimkan ke tampilan
        return view('kategori.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'kategori' => $kategori,
            'activeMenu' => $activeMenu
        ]);
    }
    // Menampilkan form edit kategori
    public function edit(string $id)
    {
        // Ambil data kategori berdasarkan id
        $kategori = KategoriModel::find($id);

        // Periksa apakah kategori ditemukan, jika tidak, kembalikan error
        if (!$kategori) {
            return redirect('/kategori')->with('error', 'Kategori tidak ditemukan');
        }

        $breadcrumb = (object) [
            'title' => 'Edit Kategori',
            'list' => ['Home', 'kategori', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit kategori'
        ];

        $activeMenu = 'kategori'; // Set menu yang sedang aktif

        // Kirim variabel kategori ke tampilan edit
        return view('kategori.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'kategori' => $kategori,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menyimpan perubahan data user
    public function update(Request $request, string $id)
    {
        $request->validate([
            'kategori_kode' => 'required|string|min:3|unique:m_kategori,kategori_kode',
            'kategori_nama' => 'required|string|max:100',
        ]);

        $level = KategoriModel::find($id);
        $level->update([
            'kategori_kode' => $request->kategori_kode,
            'kategori_nama' => $request->kategori_nama,
        ]);

        return redirect('/kategori')->with('success', 'Data kategori berhasil diubah');
    }
    // Menghapus data kategori
    public function destroy(string $kategori_id)
    {
        $check = Kategorimodel::find($kategori_id);
        if (!$check) {
            return redirect('/kategori')->with('error', 'Data kategori tidak ditemukan');
        }
        try {
            Kategorimodel::destroy($kategori_id);
            return redirect('/kategori')->with('success', 'Data kategori berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/kategori')->with('error', 'Data kategori gagal dhapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
    // create ajax
    public function create_ajax(){
        return view('kategori.create_ajax');
    }

    public function store_ajax(Request $request){
        if($request->ajax()||$request->wantsJson()){
            $rules = [
                'kategori_kode'=>'required|string|unique:m_kategori,kategori_kode',
                'kategori_nama'=>'required|string|max:100'
            ];

            $validator = Validator::make($request->all(),$rules);
            if($validator->fails()){
                return response()->json([
                    'status'=>false,
                    'message'=>'Validasi Gagal',
                    'msgField'=>$validator->errors()
                ]);
            }

            KategoriModel::create($request->all());
            return response()->json([
                'status'=>true,
                'message'=>'Data kategori berhasil disimpan'
            ]);
        }
        redirect('/');
    }
       //edit ajax 
    public function edit_ajax(string $kategori_id){
        $kategori = KategoriModel::find($kategori_id);

        return view('kategori.edit_ajax',['kategori'=>$kategori]);
    }
    public function update_ajax(Request $request, $kategori_id)
    {
        // cek apakah request dari ajax 
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_kode' => 'required|string|unique:m_kategori,kategori_kode',
                'kategori_nama'=>'required|string|max:100'
                
            ];
            // use Illuminate\Support\Facades\Validator; 
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,    // respon json, true: berhasil, false: gagal 
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors()  // menunjukkan field mana yang error 
                ]);
            }

            $check = KategoriModel::find($kategori_id);
            if ($check) {
                $check->update($request->all());
                return response()->json([
                    'status'  => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }
    public function confirm_ajax(string $kategori_id){
        $kategori   = KategoriModel::find($kategori_id);

        return view('kategori.confirm_ajax',['kategori'=>$kategori]);
    }

    public function delete_ajax(Request $request, $kategori_id)
{
    if ($request->ajax() || $request->wantsJson()) {
        $kategori = KategoriModel::find($kategori_id);
        
        if ($kategori) {
            try {
                $kategori->delete();
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
// show ajax
public function show_ajax(string $id) {
    $kategori = KategoriModel::find($id);

    if ($kategori) {
        return view('kategori.show_ajax', ['kategori' => $kategori]);
    } else {
        return response()->json([
            'status' => false,
            'message' => 'Data tidak ditemukan'
        ]);
    }
}

public function import() 
    { 
        return view('kategori.import'); 
    }
    public function import_ajax(Request $request) 
    { 
        if($request->ajax() || $request->wantsJson()){ 
            $rules = [ 
                // validasi file harus xls atau xlsx, max 1MB 
                'file_kategori' => ['required', 'mimes:xlsx', 'max:1024'] 
            ]; 
 
            $validator = Validator::make($request->all(), $rules); 
            if($validator->fails()){ 
                return response()->json([ 
                    'status' => false, 
                    'message' => 'Validasi Gagal', 
                    'msgField' => $validator->errors() 
                ]); 
            } 
 
            $file = $request->file('file_kategori');  // ambil file dari request 
 
            $reader = IOFactory::createReader('Xlsx');  // load reader file excel 
            $reader->setReadDataOnly(true);             // hanya membaca data 
            $spreadsheet = $reader->load($file->getRealPath()); // load file excel 
            $sheet = $spreadsheet->getActiveSheet();    // ambil sheet yang aktif 
 
            $data = $sheet->toArray(null, false, true, true);   // ambil data excel 
 
            $insert = []; 
            if(count($data) > 1){ // jika data lebih dari 1 baris 
                foreach ($data as $baris => $value) { 
                    if($baris > 1){ // baris ke 1 adalah header, maka lewati 
                        $insert[] = [ 
                            'kategori_kode' => $value['A'], 
                            'kategori_nama' => $value['B'],
                            'created_at' => now(), 
                        ]; 
                    } 
                } 
 
                if(count($insert) > 0){ 
                    // insert data ke database, jika data sudah ada, maka diabaikan 
                    KategoriModel::insertOrIgnore($insert);    
                } 
 
                return response()->json([ 
                    'status' => true, 
                    'message' => 'Data berhasil diimport' 
                ]); 
            }else{ 
                return response()->json([ 
                    'status' => false, 
                    'message' => 'Tidak ada data yang diimport' 
                ]); 
            } 
        } 
        return redirect('/'); 
    }
    public function export_excel()
    {
        // Ambil data dari kategorimodel
        $kategori = KategoriModel::select('kategori_kode', 'kategori_nama')->get();

        // Inisialisasi Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // Ambil sheet yang aktif

        // Set Header Kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kategori Kode');
        $sheet->setCellValue('C1', 'Kategori Nama');

        // Buat header menjadi bold
        $sheet->getStyle('A1:C1')->getFont()->setBold(true);

        // Isi data
        $no = 1; // Nomor data dimulai dari 1
        $baris = 2; // Baris data dimulai dari baris ke-2
        foreach ($kategori as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->kategori_kode);
            $sheet->setCellValue('C' . $baris, $value->kategori_nama);

            $baris++;
            $no++;
        }

        // Set ukuran kolom otomatis untuk semua kolom
        foreach (range('A', 'C') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Set judul sheet
        $sheet->setTitle('Data Kategori');

        // Buat writer untuk menulis file excel
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Kategori_' . date('Y-m-d_His') . '.xlsx';

        // Atur Header untuk Download File Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');

        // Simpan file dan kirim ke output
        $writer->save('php://output');
        exit;
    }
}