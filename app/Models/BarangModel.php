<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangModel extends Model
{
    use HasFactory;

    protected $table = 'm_barang'; 
    protected $primaryKey = 'barang_id'; 
    
    //  fillable atau guarded
    protected $fillable = ['kategori_id', 'barang_kode', 'barang_nama','harga_beli','harga_jual'];

    //relasi
    public function kategori()
    {
        return $this->belongsTo(KategoriModel::class, 'kategori_id');
    }
}