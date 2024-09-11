<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StokSeeder extends Seeder
{
    public function run()
    {
        DB::table('t_stok')->insert([
            [
                'suppllier_id' => 1,
                'barang_id' => 1,
                'user_id' => 3,
                'stok_tanggal' => Carbon::now(),
                'stok_jumlah' => 50,
            ],
            [
                'suppllier_id' => 1,
                'barang_id' => 2,
                'user_id' => 3,
                'stok_tanggal' => Carbon::now(),
                'stok_jumlah' => 30,
            ],
            [
                'suppllier_id' => 1,
                'barang_id' => 3,
                'user_id' => 3,
                'stok_tanggal' => Carbon::now(),
                'stok_jumlah' => 25,
            ],
            [
                'suppllier_id' => 2,
                'barang_id' => 4,
                'user_id' => 3,
                'stok_tanggal' => Carbon::now(),
                'stok_jumlah' => 45,
            ],
            [
                'suppllier_id' => 2,
                'barang_id' => 5,
                'user_id' => 3,
                'stok_tanggal' => Carbon::now(),
                'stok_jumlah' => 60,
            ],
            [
                'suppllier_id' => 2,
                'barang_id' => 6,
                'user_id' => 3,
                'stok_tanggal' => Carbon::now(),
                'stok_jumlah' => 70,
            ],
            [
                'suppllier_id' => 3,
                'barang_id' => 7,
                'user_id' => 3,
                'stok_tanggal' => Carbon::now(),
                'stok_jumlah' => 40,
            ],
            [
                'suppllier_id' => 3,
                'barang_id' => 8,
                'user_id' => 3,
                'stok_tanggal' => Carbon::now(),
                'stok_jumlah' => 55,
            ],
            [
                'suppllier_id' => 3,
                'barang_id' => 9,
                'user_id' => 3,
                'stok_tanggal' => Carbon::now(),
                'stok_jumlah' => 65,
            ],
            [
                'suppllier_id' => 2,
                'barang_id' => 10,
                'user_id' => 3,
                'stok_tanggal' => Carbon::now(),
                'stok_jumlah' => 35,
            ],
            [
                'suppllier_id' => 2,
                'barang_id' => 11,
                'user_id' => 3,
                'stok_tanggal' => Carbon::now(),
                'stok_jumlah' => 90,
            ],
            [
                'suppllier_id' => 2,
                'barang_id' => 12,
                'user_id' => 3,
                'stok_tanggal' => Carbon::now(),
                'stok_jumlah' => 120,
            ],
            [
                'suppllier_id' => 3,
                'barang_id' => 13,
                'user_id' => 3,
                'stok_tanggal' => Carbon::now(),
                'stok_jumlah' => 100,
            ],
            [
                'suppllier_id' => 3,
                'barang_id' => 14,
                'user_id' => 3,
                'stok_tanggal' => Carbon::now(),
                'stok_jumlah' => 85,
            ],
            [
                'suppllier_id' => 3,
                'barang_id' => 15,
                'user_id' => 3,
                'stok_tanggal' => Carbon::now(),
                'stok_jumlah' => 110,
            ],
        ]);
    }
}