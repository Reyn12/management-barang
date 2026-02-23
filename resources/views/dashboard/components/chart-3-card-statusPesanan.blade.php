<div class="grid grid-cols-3 gap-6 mb-6">
    <!-- Card Pending -->
    <div class="relative p-6 rounded-xl border border-gray-200 overflow-hidden bg-gradient-to-br from-yellow-700 to-yellow-900">
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-start gap-4">
                    <div class="bg-yellow-100 p-3 rounded-lg">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div class="text-white">
                        <h3 class="font-medium">Pesanan Pending</h3>
                        <p class="text-yellow-100/80">
                            @switch(request('period', '6m'))
                                @case('7d')
                                    7 Hari Terakhir
                                    @break
                                @case('30d')
                                    30 Hari Terakhir
                                    @break
                                @case('3m')
                                    3 Bulan Terakhir
                                    @break
                                @case('1y')
                                    1 Tahun Terakhir
                                    @break
                                @default
                                    6 Bulan Terakhir
                            @endswitch
                        </p>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-semibold text-white">{{ $pendingCount }}</div>
                <div class="text-lg text-yellow-100">Rp {{ number_format($pendingTotal, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <!-- Card Sukses -->
    <div class="relative p-6 rounded-xl border border-gray-200 overflow-hidden bg-gradient-to-br from-green-700 to-green-900">
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-start gap-4">
                    <div class="bg-green-100 p-3 rounded-lg">
                        <i class="fas fa-check text-green-600 text-xl"></i>
                    </div>
                    <div class="text-white">
                        <h3 class="font-medium">Pesanan Sukses</h3>
                        <p class="text-green-100/80">Pembayaran berhasil</p>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-semibold text-white">{{ $suksesCount }}</div>
                <div class="text-lg text-green-100">Rp {{ number_format($suksesTotal, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <!-- Card Gagal -->
    <div class="relative p-6 rounded-xl border border-gray-200 overflow-hidden bg-gradient-to-br from-red-700 to-red-900">
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-start gap-4">
                    <div class="bg-red-100 p-3 rounded-lg">
                        <i class="fas fa-times text-red-600 text-xl"></i>
                    </div>
                    <div class="text-white">
                        <h3 class="font-medium">Pesanan Gagal</h3>
                        <p class="text-red-100/80">Pembayaran gagal</p>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-semibold text-white">{{ $gagalCount }}</div>
                <div class="text-lg text-red-100">Rp {{ number_format($gagalTotal, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
</div>
