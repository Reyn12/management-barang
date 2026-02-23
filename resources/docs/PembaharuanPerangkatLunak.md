“Dokumen ini digunakan sebagai bukti unit kompetensi J.620100.047.01 – Pembaruan Perangkat Lunak pada project `management-barang`.”

# Update semua dependency Composer ke versi terbaru (sesuai constraint):

composer update

# Regenerate autoloader:

composer dump-autoload

# Install dependency sesuai composer.lock (reproducible install untuk tim):

composer install

# Instal dependency Node.js

npm install

# Setelah update — jalankan migrasi baru:

php artisan migrate

# Cek status migrasi (apakah ada yang pending?):

php artisan migrate:status

# Reset database dan isi dengan data awal (seed):

php artisan migrate:fresh --seed

# Buat symbolic link untuk storage (akses file upload):

php artisan storage:link

# Clear cache setelah update config:

php artisan optimize:clear # clear semua cache
php artisan config:clear # clear config cache
php artisan route:clear # clear route cache
php artisan view:clear # clear compiled views

# Menjalankan server pengembangan Laravel:

php artisan serve
