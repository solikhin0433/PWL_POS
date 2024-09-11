<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run()
    {
        DB::table('m_kategori')->insert([
            [
                'kategori_kode' => 'ELEK',
                'kategori_nama' => 'Elektronik',
            ],
            [
                'kategori_kode' => 'PKN',
                'kategori_nama' => 'Pakaian',
            ],
            [
                'kategori_kode' => 'KCT',
                'kategori_nama' => 'Kecantikan',
            ],
            [
                'kategori_kode' => 'SPORT',
                'kategori_nama' => 'Olahraga',
            ],
            [
                'kategori_kode' => 'FOOD',
                'kategori_nama' => 'Makanan',
            ],
        ]);
    }
}