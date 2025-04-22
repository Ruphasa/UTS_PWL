<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i=1; $i < 3 ; $i++) { 
            $data = [
                'penjualan_id' => $i,
                'user_id' => rand(4, 7),
                'penjualan_kode' => 'Penjualan '.$i,
                'penjualan_tanggal' => Carbon::now(),
            ];
            DB::table('t_penjualan')->insert($data);
        }
    }
}
