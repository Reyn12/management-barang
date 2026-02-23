<div class="grid grid-cols-2 gap-6 mb-6">
    <!-- Card Belum Bayar -->
    <div class="relative p-6 rounded-xl border border-gray-200 overflow-hidden bg-gradient-to-br from-yellow-700 to-yellow-900">
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-start gap-4">
                    <div class="bg-yellow-100 p-3 rounded-lg">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div class="text-white">
                        <h3 class="font-medium">Belum Bayar</h3>
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
                <div class="text-2xl font-semibold text-white">{{ $belumBayarCount }}</div>
                <div class="text-lg text-yellow-100">Rp {{ number_format($belumBayarTotal, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <!-- Card Sudah Bayar -->
    <div class="relative p-6 rounded-xl border border-gray-200 overflow-hidden bg-gradient-to-br from-green-700 to-green-900">
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-start gap-4">
                    <div class="bg-green-100 p-3 rounded-lg">
                        <i class="fas fa-check text-green-600 text-xl"></i>
                    </div>
                    <div class="text-white">
                        <h3 class="font-medium">Sudah Bayar</h3>
                        <p class="text-green-100/80">Pembayaran berhasil</p>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-semibold text-white">{{ $sudahBayarCount }}</div>
                <div class="text-lg text-green-100">Rp {{ number_format($sudahBayarTotal, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
</div>
