<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    public function run()
    {
        DB::table('m_barang')->insert([
            [
                'kategori_id' => 1, 
                'barang_kode' => 'BRG001',
                'barang_nama' => 'Televisi LED 32 Inch',
                'harga_beli' => 1500000,
                'harga_jual' => 1800000,
            ],
            [
                'kategori_id' => 1, 
                'barang_kode' => 'BRG002',
                'barang_nama' => 'Televisi Tabung',
                'harga_beli' => 1200000,
                'harga_jual' => 1500000,
            ],
            [
                'kategori_id' => 1,
                'barang_kode' => 'BRG003',
                'barang_nama' => 'Laptop Core i5',
                'harga_beli' => 5000000,
                'harga_jual' => 5500000,
            ],
            [
                'kategori_id' => 2, 
                'barang_kode' => 'BRG004',
                'barang_nama' => 'Jaket Kulit Pria',
                'harga_beli' => 300000,
                'harga_jual' => 350000,
            ],
            [
                'kategori_id' => 2, 
                'barang_kode' => 'BRG005',
                'barang_nama' => 'Celana Jeans',
                'harga_beli' => 200000,
                'harga_jual' => 250000,
            ],
            [
                'kategori_id' => 2, 
                'barang_kode' => 'BRG006',
                'barang_nama' => 'Hodie',
                'harga_beli' => 100000,
                'harga_jual' => 150000,
            ],
            [
                'kategori_id' => 3, 
                'barang_kode' => 'BRG007',
                'barang_nama' => 'Pelembab Wajah',
                'harga_beli' => 50000,
                'harga_jual' => 75000,
            ],
            [
                'kategori_id' => 3, 
                'barang_kode' => 'BRG008',
                'barang_nama' => 'Masker Muka',
                'harga_beli' => 50000,
                'harga_jual' => 55000,
            ],
            [
                'kategori_id' => 3, 
                'barang_kode' => 'BRG009',
                'barang_nama' => 'Lotion',
                'harga_beli' => 40000,
                'harga_jual' => 50000,
            ],
            [
                'kategori_id' => 4,
                'barang_kode' => 'BRG010',
                'barang_nama' => 'Sepatu Lari',
                'harga_beli' => 400000,
                'harga_jual' => 450000,
            ],
            [
                'kategori_id' => 4, 
                'barang_kode' => 'BRG011',
                'barang_nama' => 'Sauna',
                'harga_beli' => 100000,
                'harga_jual' => 150000,
            ],
            [
                'kategori_id' => 4, 
                'barang_kode' => 'BRG012',
                'barang_nama' => 'Jersey Bola',
                'harga_beli' => 100000,
                'harga_jual' => 120000,
            ],

            [
                'kategori_id' => 5, 
                'barang_kode' => 'BRG013',
                'barang_nama' => 'Mie Sedap',
                'harga_beli' => 100000,
                'harga_jual' => 120000,
            ],
            [
                'kategori_id' => 5, 
                'barang_kode' => 'BRG014',
                'barang_nama' => 'Nuget',
                'harga_beli' => 50000,
                'harga_jual' => 75000,
            ],
            [
                'kategori_id' => 5, 
                'barang_kode' => 'BRG015',
                'barang_nama' => 'Sosis',
                'harga_beli' => 20000,
                'harga_jual' => 25000,
            ],
        ]);
    }
}