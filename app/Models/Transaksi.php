<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggalpemesanan',
        'codemidtrans',
        'produk_id',
        'jumlah',
        'totalbayar',
        'paymentstat',  
    ];

    /**
     * Relasi ke model Produk
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    /**
     * Query Scope untuk TotalBayar
     */
    public function scopeTotalBayar($query)
    {
        return $query->where('paymentstat', 'completed'); // Contoh kondisi
    }

    /**
     * Fungsi Incomes untuk menghitung total income
     */
    public function incomes()
    {
        return $this->where('paymentstat', 'completed')->sum('totalbayar');
    }
}
