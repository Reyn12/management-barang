<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $primaryKey = 'id_transaksi';
    protected $fillable = [
        'kode_transaksi',
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
            // Jika belum ada transaksi, mulai dari TRX-0001
            return 'TRX-0001';
        }

        // Ambil nomor dari kode terakhir
        $lastNumber = intval(substr($lastTransaksi->kode_transaksi, 4));
        $newNumber = $lastNumber + 1;
        
        // Format nomor baru dengan padding 4 digit
        return 'TRX-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    // Relasi ke Produk (many-to-one)
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }
}
 