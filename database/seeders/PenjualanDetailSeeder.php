<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mengambil semua data barang
        
        for ($i=1; $i < 5; $i++) { 
            // Mengambil data barang sesuai dengan id
            $idx = rand(1, 15);
            $barang = DB::table('m_barang')->where('barang_id', $idx)->first();
            $harga = $barang->harga_jual;
            $jumlah = rand(1, 10);
            $hargaSubTotal = $harga * $jumlah;
            $data = [
                'detail_id' => $i,
                'penjualan_id' => rand(1,2),
                'barang_id' => $idx,
                'jumlah' => $jumlah,
                'harga' => $hargaSubTotal,
            ];
            DB::table('t_penjualan_detail')->insert($data);
        }
    }
}
