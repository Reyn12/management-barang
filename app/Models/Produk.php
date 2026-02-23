<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $primaryKey = 'id_produk';
    protected $fillable = [
        'id_supplier',
        'nama_produk',
        'kategori',
        'harga',
        'stok',
        'spesifikasi',
        'foto_produk'
    ];

    // Relasi ke Supplier (many-to-one)
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }

    // Relasi ke Transaksi (one-to-many)
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'id_produk');
    }

    public function getFotoUrlAttribute()
    {
        if (!$this->foto_produk) {
            return null;
        }

        // Kalo path nya udah pake format baru (produk/...)
        if (str_starts_with($this->foto_produk, 'produk/')) {
            return asset('storage/' . $this->foto_produk);
        }

        // Kalo masih pake format lama (images/products/...)
        if (str_starts_with($this->foto_produk, 'images/products/')) {
            return asset($this->foto_produk);
        }

        return asset('storage/produk/' . $this->foto_produk);
    }
}