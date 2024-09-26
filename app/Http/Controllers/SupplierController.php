<?php

namespace App\Http\Controllers;
use App\Models\SupplierModel;
use Yajra\DataTables\Facades\DataTables; 

use Illuminate\Http\Request;

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
    public function list(Request $request){
        $supplier = suppliermodel::select('suppllier_id','supplier_kode','supplier_nama','supplier_alamat');
        if($request->suppllier_id){
            $supplier->where('suppllier_id',$request->supplier_id);
        }
        return DataTables::of($supplier)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($supplier) { // menambahkan kolom aksi
                $btn = '<a href="' . url('/supplier/' . $supplier->suppllier_id) . '" class="btn btn-info btnsm">Detail</a> ';
                $btn .= '<a href="' . url('/supplier/' . $supplier->suppllier_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/supplier/' . $supplier->suppllier_id) . '">'
                    . csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
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
            'supplier_alamat'=>'required|string|max:100'
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
}