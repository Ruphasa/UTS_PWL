<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'kategori_id' => 1,
                'barang_kode' => 'B001',
                'barang_nama' => 'Indomie Goreng',
                'harga_beli' => 2700,
                'harga_jual' => 3500
            ],
            [
                'kategori_id' => 1,
                'barang_kode' => 'B002',
                'barang_nama' => 'Indomie Kuah',
                'harga_beli' => 2800,
                'harga_jual' => 3800
            ],
            [
                'kategori_id' => 2,
                'barang_kode' => 'B003',
                'barang_nama' => 'Coca-Cola 330ml',
                'harga_beli' => 4500,
                'harga_jual' => 5500
            ],
            [
                'kategori_id' => 2,
                'barang_kode' => 'B004',
                'barang_nama' => 'Sprite 330ml',
                'harga_beli' => 4700,
                'harga_jual' => 5800
            ],
            [
                'kategori_id' => 3,
                'barang_kode' => 'B005',
                'barang_nama' => 'Tepung Terigu Segitiga Biru 1kg',
                'harga_beli' => 12000,
                'harga_jual' => 15000
            ],
            [
                'kategori_id' => 3,
                'barang_kode' => 'B006',
                'barang_nama' => 'Margarin Selera 250g',
                'harga_beli' => 10000,
                'harga_jual' => 13000
            ],
            [
                'kategori_id' => 4,
                'barang_kode' => 'B007',
                'barang_nama' => 'Pepsodent Salt Toothpaste 150g',
                'harga_beli' => 12000,
                'harga_jual' => 15000
            ],
            [
                'kategori_id' => 5,
                'barang_kode' => 'B008',
                'barang_nama' => 'Dettol 2in1 Original 100ml',
                'harga_beli' => 15000,
                'harga_jual' => 18000
            ],
            [
                'kategori_id' => 6,
                'barang_kode' => 'B009',
                'barang_nama' => 'Pensil 2B',
                'harga_beli' => 1000,
                'harga_jual' => 1500
            ],
            [
                'kategori_id' => 6,
                'barang_kode' => 'B010',
                'barang_nama' => 'Pulpen Hitam',
                'harga_beli' => 2000,
                'harga_jual' => 3000
            ],
            [
                'kategori_id' => 7,
                'barang_kode' => 'B011',
                'barang_nama' => 'Sarden Kaleng ABC 180g',
                'harga_beli' => 6000,
                'harga_jual' => 8000
            ],
            [
                'kategori_id' => 8,
                'barang_kode' => 'B012',
                'barang_nama' => 'Kecap Manis Bango 100ml',
                'harga_beli' => 5000,
                'harga_jual' => 7000
            ],
            [
                'kategori_id' => 9,
                'barang_kode' => 'B013',
                'barang_nama' => 'Sari Roti 150g',
                'harga_beli' => 7000,
                'harga_jual' => 9000
            ],
            [
                'kategori_id' => 9,
                'barang_kode' => 'B014',
                'barang_nama' => 'Chips',
                'harga_beli' => 7000,
                'harga_jual' => 9000
            ],
            [
                'kategori_id' => 10,
                'barang_kode' => 'B015',
                'barang_nama' => 'Perekat Invisible Tape',
                'harga_beli' => 8000,
                'harga_jual' => 12000
            ]
        ];

        DB::table('m_barang')->insert($data);
    }
}
