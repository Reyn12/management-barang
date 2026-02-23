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

    // Relasi ke Produk (one-to-many) J.620100.021.02
    // Relasi: satu supplier punya banyak produk
    // SQL: SELECT * FROM produks WHERE id_supplier = {supplier.id_supplier}
    public function produks()
    {
        return $this->hasMany(Produk::class, 'id_supplier');
    }
}
