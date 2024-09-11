<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class detailpenjualanseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];

        $detail_id = 1;
        for ($penjualan_id = 1; $penjualan_id <= 10; $penjualan_id++) {
            for ($barang_id = 1; $barang_id <= 3; $barang_id++) {
                $data[] = [
                    'detail_id' => $detail_id,
                    'penjualan_id' => $penjualan_id,
                    'barang_id' => $barang_id,
                    'harga' => rand(100000, 1500000), 
                    'jumlah' => rand(1, 10), 
                ];
                $detail_id++;
            }
        }

        DB::table('t_penjualan_detail')->insert($data);
    }
}