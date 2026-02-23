# J.620100.036.02 – Pengujian Statis (PHPUnit / Pest)

Di project `management-barang` saya menggunakan PHPUnit yang dikombinasikan dengan Pest sebagai test runner:

- Konfigurasi PHPUnit ada di `phpunit.xml` yang mendefinisikan test suite `Unit` dan `Feature`.
- File test utama disimpan di folder `tests/Feature` dan `tests/Unit`.
- Contoh pengujian feature:
    - `tests/Feature/ExampleTest.php` melakukan HTTP request ke route `/` dan memastikan response status `200`.
- Contoh pengujian unit:
    - `tests/Unit/ExampleTest.php` memverifikasi assertion dasar menggunakan Pest (`expect(true)->toBeTrue()`).

Pengujian dijalankan dengan perintah:

php artisan test

# atau

vendor/bin/phpunit
