# J.620100.032.01 – Code Review dan Separation of Concerns

Di project `management-barang` saya menerapkan prinsip Code Review dan Separation of Concerns:

- **Pemisahan layer MVC**: Controller hanya menangani HTTP request/response dan validasi, view Blade hanya untuk tampilan, sedangkan model (`Produk`, `Supplier`, `Transaksi`, `User`) hanya menyimpan data dan relasi.
- **Contoh pada `SystemMonitorController`**:
    - Method `index()` hanya mengatur alur tampilan dashboard monitoring.
    - Perhitungan ukuran database dan storage dipisah ke method privat `getDatabaseInfo()` dan `getDirectorySize()` sehingga mudah di-review dan diuji terpisah.
- **Contoh pada `AboutToolsController`**:
    - Method `index()` hanya membangun array `$tools` untuk dikirim ke view.
    - Logika deteksi versi tool (Composer, Node.js, NPM, Vite, Tailwind) dipisah ke method privat `getComposerVersion()`, `getNodeVersion()`, `getNpmVersion()`, `getViteVersion()`, `getTailwindVersion()`, dan helper `runCommand()`.
- Dengan pemisahan ini, setiap method/class memiliki satu tanggung jawab utama (Single Responsibility Principle), dan proses Code Review menjadi lebih mudah karena kode lebih terstruktur dan tidak menumpuk di satu tempat.
