<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolPurchase extends Model
{
    use HasFactory;

    protected $table = 'school_purchases';

    protected $fillable = [
        'tanggal_pembelian',
        'nama_barang',
        'kode',
        'harga_satuan',
        'jumlah_baik',
        'jumlah_rusak',
        'total_harga',
        'pembeli',
        'toko',
        'deskripsi',
        'gambar',
        'keterangan',
    ];

    public function images()
    {
        return $this->hasMany(SchoolPurchaseImage::class);
    }
}
