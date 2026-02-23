<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Manajemen Barang</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Lato', sans-serif; }
        .swal-loading-square.swal2-popup {
            width: 160px !important;
            height: 160px !important;
            padding: 1.25rem !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            box-sizing: border-box;
        }
        .swal-loading-square .swal2-title { margin: 0 0 0.75rem 0 !important; font-size: 0.95rem; }
        .swal-loading-square .swal2-loader { margin: 0 !important; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-500 rounded-2xl shadow-lg shadow-blue-500/30 mb-4">
                <i class="fas fa-box text-white text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Buat Akun Baru</h1>
            <p class="text-gray-500 mt-1">Daftar untuk mengakses sistem</p>
        </div>

        {{-- Register Card --}}
        <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 p-8">
            
            {{-- Error Message --}}
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-6">
                    <div class="flex items-center gap-3 mb-1">
                        <i class="fas fa-exclamation-circle"></i>
                        <span class="text-sm font-medium">Terjadi kesalahan:</span>
                    </div>
                    <ul class="text-sm list-disc list-inside ml-6">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="formRegister" method="POST" action="{{ route('register.store') }}">
                @csrf

                {{-- Nama --}}
                <div class="mb-5">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name') }}"
                            class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all duration-200 outline-none"
                            placeholder="Nama lengkap"
                            required
                            autofocus
                        >
                    </div>
                </div>

                {{-- Email --}}
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all duration-200 outline-none"
                            placeholder="email@example.com"
                            required
                        >
                    </div>
                </div>

                {{-- Password --}}
                <div class="mb-5">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <div class="relative" x-data="{ show: false }">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input 
                            :type="show ? 'text' : 'password'" 
                            id="password" 
                            name="password"
                            class="w-full pl-11 pr-12 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all duration-200 outline-none"
                            placeholder="Minimal 6 karakter"
                            required
                        >
                        <button 
                            type="button"
                            @click="show = !show"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                        >
                            <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                        </button>
                    </div>
                </div>

                {{-- Konfirmasi Password --}}
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                    <div class="relative" x-data="{ show: false }">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input 
                            :type="show ? 'text' : 'password'" 
                            id="password_confirmation" 
                            name="password_confirmation"
                            class="w-full pl-11 pr-12 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all duration-200 outline-none"
                            placeholder="Ulangi password"
                            required
                        >
                        <button 
                            type="button"
                            @click="show = !show"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                        >
                            <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                        </button>
                    </div>
                </div>

                {{-- Submit --}}
                <button 
                    type="submit"
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-4 rounded-xl transition-all duration-200 shadow-lg shadow-blue-500/30 hover:shadow-blue-500/40 active:scale-[0.98]"
                >
                    <i class="fas fa-user-plus mr-2"></i>
                    Daftar
                </button>
            </form>
        </div>

        <p class="text-center text-sm mt-6 text-gray-500">
            Sudah punya akun? 
            <a href="{{ route('login') }}" class="text-blue-500 hover:text-blue-600 font-medium">Masuk</a>
        </p>
    </div>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('formRegister').addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Memproses...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                customClass: { popup: 'swal-loading-square' },
                didOpen: function() { Swal.showLoading(); }
            });
            var form = this;
            setTimeout(function() { form.submit(); }, 1000);
        });
    </script>
</body>
</html>
