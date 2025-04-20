<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class penjualanModel extends Model
{
    use HasFactory;

    protected $table = 'm_penjualan';
    protected $primaryKey = 'penjualan_id';

    protected $fillable = [
        'kategori_id',
        'penjualan_nama',
        'penjualan_kode',
        'harga_beli',
        'harga_jual'
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriModel::class, 'kategori_id', 'kategori_id');
    }
}
