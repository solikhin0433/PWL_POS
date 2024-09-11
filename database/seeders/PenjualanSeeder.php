<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('t_penjualan')->insert([
            [
                'user_id' => 3,
                'pembeli' => 'Budi',
                'penjualan_kode' => 'ABC001',
                'penjualan_tanggal' => Carbon::now(),
            ],
            [
                'user_id' => 3,
                'pembeli' => 'Budi',
                'penjualan_kode' => 'ABC002',
                'penjualan_tanggal' => Carbon::now(),
            ],
            [
                'user_id' => 3,
                'pembeli' => 'Budi',
                'penjualan_kode' => 'ABC003',
                'penjualan_tanggal' => Carbon::now(),
            ],
            [
                'user_id' => 3,
                'pembeli' => 'Solikhin',
                'penjualan_kode' => 'TRX004',
                'penjualan_tanggal' => Carbon::now(),
            ],
            [
                'user_id' => 3,
                'pembeli' => 'Solikhin',
                'penjualan_kode' => 'TRX005',
                'penjualan_tanggal' => Carbon::now(),
            ],
            [
                'user_id' => 3,
                'pembeli' => 'Solikhin',
                'penjualan_kode' => 'TRX006',
                'penjualan_tanggal' => Carbon::now(),
            ],
            [
                'user_id' => 3,
                'pembeli' => 'Risky',
                'penjualan_kode' => 'RSY007',
                'penjualan_tanggal' => Carbon::now(),
            ],
            [
                'user_id' => 3,
                'pembeli' => 'Risky',
                'penjualan_kode' => 'RSY008',
                'penjualan_tanggal' => Carbon::now(),
            ],
            [
                'user_id' => 3,
                'pembeli' => 'Risky',
                'penjualan_kode' => 'RSY009',
                'penjualan_tanggal' => Carbon::now(),
            ],
            [
                'user_id' => 3,
                'pembeli' => 'Risky',
                'penjualan_kode' => 'RSY010',
                'penjualan_tanggal' => Carbon::now(),
            ],
        ]);
    }
}