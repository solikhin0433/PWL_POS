<?php

namespace App\Http\Controllers;
use App\Models\SupplierModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\Facades\DataTables; 
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory; 
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
class SupplierController extends Controller
{
    public function index(){
        $breadcrumb = (object)[
            'title'=>'Daftar supplier',
            'list'=>['Home','supplier']
        ];
        $page =(object)[
            'title'=>'Daftar supplier yang terdaftar dalam sistem'
        ];
        $activeMenu ='supplier';
        $supplier = suppliermodel::all();
        return view('supplier.index',['breadcrumb'=>$breadcrumb,'page'=>$page,'supplier'=>$supplier, 'activeMenu' =>$activeMenu]);
    }
    // ambil data user dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $supplier = SupplierModel::all();
        return DataTables::of($supplier)
            ->addIndexColumn()
            ->addColumn('aksi', function ($supplier) {
                /*$btn = '<a href="' . url('/supplier/' . $supplier->supplier_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url('/supplier/' . $supplier->supplier_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/supplier/' . $supplier->supplier_id) . '">'
                    . csrf_field() . method_field('DELETE') . 
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';*/
                $btn  = '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->suppllier_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->suppllier_id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->suppllier_id .
                    '/delete_ajax') . '\')"  class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create(){
        $breadcrumb = (object)[
            'title'=>'Tambah supplier',
            'list'=>['Home','supplier','tambah']
        ];
        $page = (object)[
            'title'=>'Tambah supplier baru'
        ];
        $activeMenu = 'supplier';
        $supplier = suppliermodel::all();
        return view('supplier.create',['breadcrumb'=>$breadcrumb,'page'=>$page,'activeMenu'=>$activeMenu,'supplier'=>$supplier]);
    }

    public function store(Request $request){
        $request->validate([
            'supplier_kode'=>'required|string|min:3|max:10|unique:m_supplier,supplier_kode',
            'supplier_nama'=>'required|string|max:100',
            'supplier_alamat'=>'required|string|max:225'
        ]);
        suppliermodel::create([
            'supplier_kode'=>$request->supplier_kode,
            'supplier_nama'=>$request->supplier_nama,
            'supplier_alamat'=>$request->supplier_alamat,
        ]);
        return redirect('/supplier')->with('success','Data supplier berhasil disimpan');
    }

    public function show(string $suppllier_id){
        $supplier = suppliermodel::find($suppllier_id);
        $breadcrumb = (object)[
            'title'=>'Detail Supplier',
            'list'=>['Home','Supplier','Detail']
        ];
        $page = (object)[
            'title'=>'Detail Supplier'
        ];
        $activeMenu = 'supplier';
        return view('supplier.show',['breadcrumb'=>$breadcrumb,'page'=>$page,'activeMenu'=>$activeMenu,'supplier'=>$supplier]);
    }

    public function edit(string $suppllier_id){
        $supplier = suppliermodel::find($suppllier_id);
        $breadcrumb = (object)[
            'title'=>'Edit supplier',
            'list'=>['Home','supplier','edit']
        ];
        $page = (object)[
            'title' => 'Edit supplier'
        ];
        $activeMenu = 'supplier';
        return view('supplier.edit',['breadcrumb'=>$breadcrumb,'page'=>$page,'supplier'=>$supplier,'activeMenu'=>$activeMenu]);
    }

    public function update(Request $request, string $suppllier_id){
        $request->validate([
            'supplier_kode'=>'required|string|min:3|max:10|unique:m_supplier,supplier_kode',
            'supplier_nama'=>'required|string|max:100',
            'supplier_alamat'=>'required|string|max:100'
        ]);
        $supplier = suppliermodel::find($suppllier_id);
        $supplier->update([
            'supplier_kode'=>$request->supplier_kode,
            'supplier_nama'=>$request->supplier_nama,
            'supplier_alamat'=>$request->supplier_alamat
        ]);
        return redirect('/supplier')->with('success','Data supplier berhasil diperbarui');
    }

    public function destroy(string $suppllier_id){
        $check = suppliermodel::find($suppllier_id);
        if (!$check) {
            return redirect('/supplier')->with('error', 'Data level tidak ditemukan');
        }
        try {
            suppliermodel::destroy($suppllier_id);
            return redirect('/supplier')->with('success', 'Data Supplier berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/supplier')->with('error', 'Data Supplier gagal dhapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
    //create ajax
    public function create_ajax(){
        return view('supplier.create_ajax');
    }
    // store  ajax
    public function store_ajax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'supplier_kode' => 'required|min:2|max:10',
            'supplier_nama' => 'required|min:3|max:100',
            'supplier_alamat' => 'required|min:5|max:255' // Menyesuaikan field alamat supplier
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'msgField' => $validator->errors()
            ]);
        }

        $supplier = new SupplierModel();
        $supplier->supplier_kode = $request->supplier_kode;
        $supplier->supplier_nama = $request->supplier_nama;
        $supplier->supplier_alamat = $request->supplier_alamat; // Menyimpan alamat supplier
        $supplier->save();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil disimpan'
        ]);
    }
    public function edit_ajax($suppllier_id)
    {
        $supplier = SupplierModel::find($suppllier_id);
        return view('supplier.edit_ajax', ['supplier' => $supplier]);
    }

    public function update_ajax(Request $request, $suppllier_id)
    {
        $validator = Validator::make($request->all(), [
            'supplier_kode' => 'required|min:3|max:10',
            'supplier_nama' => 'required|min:3|max:100',
            'supplier_alamat' => 'required|min:5|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'msgField' => $validator->errors()
            ]);
        }

        $supplier = SupplierModel::find($suppllier_id);
        if ($supplier) {
            $supplier->supplier_kode = $request->supplier_kode;
            $supplier->supplier_nama = $request->supplier_nama;
            $supplier->supplier_alamat = $request->supplier_alamat;
            $supplier->save();

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diperbarui'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }
    // confirm ajax
    public function confirm_ajax($id)
    {
        $supplier = SupplierModel::find($id);
        return view('supplier.confirm_ajax', ['supplier' => $supplier]);
    }
    // delete ajax
    public function delete_ajax(Request $request, $suppllier_id)
{
    if ($request->ajax() || $request->wantsJson()) {
        $supplier = SupplierModel::find($suppllier_id);
        
        if ($supplier) {
            try {
                $supplier->delete();
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
    // Cari level berdasarkan id
    $supplier = supplierModel::find($id);

    // Periksa apakah level ditemukan
    if ($supplier) {
        // Tampilkan halaman show_ajax dengan data supplier
        return view('supplier.show_ajax', ['supplier' => $supplier]);
    } else {
        // Tampilkan pesan kesalahan jika supplier tidak ditemukan
        return response()->json([
            'status' => false,
            'message' => 'Data tidak ditemukan'
        ]);
    }
}

public function import() 
    { 
        return view('supplier.import'); 
    }
    public function import_ajax(Request $request) 
    { 
        if($request->ajax() || $request->wantsJson()){ 
            $rules = [ 
                // validasi file harus xls atau xlsx, max 1MB 
                'file_supplier' => ['required', 'mimes:xlsx', 'max:1024'] 
            ]; 
 
            $validator = Validator::make($request->all(), $rules); 
            if($validator->fails()){ 
                return response()->json([ 
                    'status' => false, 
                    'message' => 'Validasi Gagal', 
                    'msgField' => $validator->errors() 
                ]); 
            } 
 
            $file = $request->file('file_supplier');  // ambil file dari request 
 
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
                            'supplier_kode' => $value['A'], 
                            'supplier_nama' => $value['B'],
                            'supplier_alamat' => $value['C'],
                            'created_at' => now(), 
                        ]; 
                    } 
                } 
 
                if(count($insert) > 0){ 
                    // insert data ke database, jika data sudah ada, maka diabaikan 
                    SupplierModel::insertOrIgnore($insert);    
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
        $supplier = SupplierModel::select('supplier_kode', 'supplier_nama','supplier_alamat')->get();

        // Inisialisasi Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // Ambil sheet yang aktif

        // Set Header Kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Supplier Kode');
        $sheet->setCellValue('C1', 'Supplier Nama');
        $sheet->setCellValue('D1', 'Supplier Alamat');

        // Buat header menjadi bold
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);

        // Isi data
        $no = 1; // Nomor data dimulai dari 1
        $baris = 2; // Baris data dimulai dari baris ke-2
        foreach ($supplier as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->supplier_kode);
            $sheet->setCellValue('C' . $baris, $value->supplier_nama);
            $sheet->setCellValue('D'. $baris, $value->supplier_alamat);

            $baris++;
            $no++;
        }

        // Set ukuran kolom otomatis untuk semua kolom
        foreach (range('A', 'D') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Set judul sheet
        $sheet->setTitle('Data Supplier');

        // Buat writer untuk menulis file excel
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Supplier_' . date('Y-m-d_His') . '.xlsx';

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
    public function export_pdf()
{
    // Ambil data barang dari database
    $barang = SupplierModel::select('supplier_kode', 'supplier_nama', 'supplier_alamat')

        ->get();

    // Gunakan library Dompdf untuk membuat PDF
    $pdf = Pdf::loadView('supplier.export_pdf', ['supplier' => $barang]);

    // Atur ukuran kertas dan orientasi
    $pdf->setPaper('A4', 'portrait');

    // Aktifkan opsi untuk memuat gambar dari URL (jika ada)
    $pdf->setOption('isRemoteEnabled', true);

    // Render PDF
    $pdf->render();

    // Download PDF
    return $pdf->stream('Data Supplier ' . date('Y-m-d H:i:s') . '.pdf');
}
}