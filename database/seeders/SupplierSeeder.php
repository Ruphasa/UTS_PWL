<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'supplier_kode' => 'S001',
                'supplier_nama' => 'PT Indofood Sukses Makmur',
                'supplier_alamat' => 'Jl. Raya Bogor Km 5,5 Cibinong'
            ],
            [
                'supplier_kode' => 'S002',
                'supplier_nama' => 'Coca-Cola Indonesia',
                'supplier_alamat' => 'Jl. Jenderal Sudirman Kav. 24-25 Jakarta'
            ],
            [
                'supplier_kode' => 'S003',
                'supplier_nama' => 'PT Mondelez Indonesia',
                'supplier_alamat' => 'Jl. Gatot Subroto Kav 18 Jakarta'
            ],
            [
                'supplier_kode' => 'S004',
                'supplier_nama' => 'PT Unilever Indonesia',
                'supplier_alamat' => 'Jl. Sudirman Kav 26-27 Jakarta'
            ],
            [
                'supplier_kode' => 'S005',
                'supplier_nama' => 'CV Royal Jaya',
                'supplier_alamat' => 'Jl. Ciliwung No. 10 Bogor'
            ],
            [
                'supplier_kode' => 'S006',
                'supplier_nama' => 'PT Ace Hardware Indonesia Tbk',
                'supplier_alamat' => 'Jl. MOG Malang'
            ],
            [
                'supplier_kode' => 'S007',
                'supplier_nama' => 'PT Kalbe Farma Tbk',
                'supplier_alamat' => 'Jl. Raya Bogor Km 5,5 Cibinong'
            ]
        ];
        DB::table('m_supplier')->insert($data);
    }
}
