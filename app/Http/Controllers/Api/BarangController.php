<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BarangModel;

class BarangController extends Controller
{
    public function index()
    {
        return BarangModel::all();
    }

    public function store(Request $request)
    {
        // Handle the avatar upload, if provided
        $filename = null;
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $path = $avatar->storeAs('public/gambar', $avatar->hashName());
            $filename = basename($path);
        }

        // Create barang with the uploaded or null avatar
        $barang = BarangModel::create([
            'kategori_id' => $request->kategori_id,
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'avatar' => $filename
        ]);

        return response()->json($barang, 201);
    }

    public function show(BarangModel $barang)
    {
        return BarangModel::find($barang->barang_id);
    }

    public function update(Request $request, BarangModel $barang)
    {
        // Handle the avatar upload, if provided
        $filename = $barang->avatar; // Retain existing avatar if not updated
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $path = $avatar->storeAs('public/gambar', $avatar->hashName());
            $filename = basename($path);
        }

        // Update barang with the provided data, keeping others unchanged
        $barang->fill([
            'kategori_id' => $request->kategori_id ?? $barang->kategori_id,
            'barang_kode' => $request->barang_kode ?? $barang->barang_kode,
            'barang_nama' => $request->barang_nama ?? $barang->barang_nama,
            'harga_beli' => $request->harga_beli ?? $barang->harga_beli,
            'harga_jual' => $request->harga_jual ?? $barang->harga_jual,
            'avatar' => $filename
        ]);

        $barang->save();

        return response()->json($barang);
    }

    public function destroy(BarangModel $barang)
    {
        $barang->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data terhapus',
        ]);
    }
}