<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        DB::table('m_supplier')->insert([
            [
                'supplier_kode' => 'SUPP001',
                'supplier_nama' => 'PT. Sumber Jaya',
                'supplier_alamat' => 'Jl. Merdeka No. 6, Jakarta',
            ],
            [
                'supplier_kode' => 'SUPP002',
                'supplier_nama' => 'PT. Berkah Jaya',
                'supplier_alamat' => 'Jl. Sudirman No. 7, Ngawi',
            ],
            [
                'supplier_kode' => 'SUPP003',
                'supplier_nama' => 'PT. Toko Jaya',
                'supplier_alamat' => 'Jl. Diponegoro No. 12, Madiun',
            ],
        ]);
    }
}