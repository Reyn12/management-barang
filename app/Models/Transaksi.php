<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $primaryKey = 'id_transaksi';
    protected $fillable = [
        'kode_transaksi',
        'nama_customer',
        'id_produk',
        'tgl_jual',
        'jumlah',
        'total_harga',
        'status_bayar'
    ];

    public static function generateKodeTransaksi()
    {
        $lastTransaksi = self::orderBy('id_transaksi', 'desc')->first();
        
        if (!$lastTransaksi) {
            return 'TRX-0001';
        }

        $lastNumber = intval(substr($lastTransaksi->kode_transaksi, 4));
        $newNumber = $lastNumber + 1;
        
        return 'TRX-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }
}
 