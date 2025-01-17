<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'image',
        'namaproduk',
        'slug',
        'jeniskopi',
        'tingkatroasting',
        'proseskopi',
        'harga',
        'deskripsi'
    ];

    public function transaksis(){
        return $this->hasMany(Transaksi::class);
    }

}
