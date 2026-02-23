<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $primaryKey = 'id_supplier';
    protected $fillable = [
        'nama_supplier',
        'alamat',
        'no_telp',
        'email'
    ];

    // Relasi ke Produk (one-to-many)
    public function produks()
    {
        return $this->hasMany(Produk::class, 'id_supplier');
    }
}
