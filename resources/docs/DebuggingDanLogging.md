# J.620100.025.02 – Debugging dan Logging

**Penerapan di project `management-barang`:**

- Di `TransaksiController@store`, `ProdukController@update`, dan `SupplierController@store/update` saya menerapkan pola `try/catch` dan menulis log dengan `Log::info()` dan `Log::error()`.
- `Log::info` dipakai untuk:
    - Mencatat parameter filter, query yang dieksekusi, dan data sebelum/sesudah update (contoh di `TransaksiController@index` dan `ProdukController@update`).
- `Log::error` dipakai untuk:
    - Mencatat detail error (message + trace) ketika operasi create/update gagal.
- Hasil log tersimpan di `storage/logs/laravel.log`, sehingga bisa dipakai untuk debugging tanpa menampilkan detail error teknis ke user.
