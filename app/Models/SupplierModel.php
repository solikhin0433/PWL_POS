<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class suppliermodel extends Model
{
    protected $table ='m_supplier';
    protected $primaryKey = 'suppllier_id';

    protected $fillable = ['suppllier_id','supplier_kode','supplier_nama','supplier_alamat'];
}