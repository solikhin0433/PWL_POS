<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class BarangModel extends Model
{
    use HasFactory;

    protected $table = 'm_barang'; 
    protected $primaryKey = 'barang_id'; 
    
    // Kolom yang boleh diisi
    protected $fillable = ['kategori_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual', 'avatar'];

    // Relasi
    public function kategori()
    {
        return $this->belongsTo(KategoriModel::class, 'kategori_id');
    }

    // Accessor untuk avatar
    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: fn ($avatar) => $avatar ? url('storage/avatars/'.$avatar) : null // Menyimpan di folder storage
        );
    }
}