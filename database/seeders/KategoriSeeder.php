<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['kategori_kode' => 'MINT', 'kategori_nama' => 'Mie Instan'],
            ['kategori_kode' => 'MNMN', 'kategori_nama' => 'Minuman'],
            ['kategori_kode' => 'BHKE', 'kategori_nama' => 'Bahan Kue'],
            ['kategori_kode' => 'PRPR', 'kategori_nama' => 'Produk Perawatan'],
            ['kategori_kode' => 'PRPB', 'kategori_nama' => 'Produk Pembersih'],
            ['kategori_kode' => 'ALTT', 'kategori_nama' => 'Alat Tulis'],
            ['kategori_kode' => 'MKNKLG', 'kategori_nama' => 'Makanan Kaleng'],
            ['kategori_kode' => 'BMBM', 'kategori_nama' => 'Bumbu Masak'],
            ['kategori_kode' => 'SNCK', 'kategori_nama' => 'Snack'],
            ['kategori_kode' => 'OHTR', 'kategori_nama' => 'Produk Lainnya']
        ];
        DB::table('m_kategori')->insert($data);
    }
}
