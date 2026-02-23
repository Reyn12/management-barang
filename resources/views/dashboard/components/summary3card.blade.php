<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 mt-4">
        <!-- Card Total Produk -->
        <div class="relative rounded-xl shadow-sm border border-gray-200 bg-gradient-to-br from-blue-900 to-blue-800">
            <div class="p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-sm text-blue-200 mb-1">Total Produk</div>
                        <div class="text-2xl font-semibold text-white">{{ $totalProduk }}</div>
                    </div>
                    <div class="rounded-lg bg-blue-800/50 p-3">
                        <i class="fas fa-box text-blue-200"></i>
                    </div>
                </div>
                <div class="flex items-center gap-1 mt-3">
                    @if($persentaseProduk > 0)
                        <i class="fas fa-arrow-up text-emerald-400 text-sm"></i>
                        <div class="text-emerald-400 text-sm">{{ number_format($persentaseProduk, 1) }}%</div>
                    @elseif($persentaseProduk < 0)
                        <i class="fas fa-arrow-down text-red-400 text-sm"></i>
                        <div class="text-red-400 text-sm">{{ number_format(abs($persentaseProduk), 1) }}%</div>
                    @else
                        <i class="fas fa-minus text-blue-200 text-sm"></i>
                        <div class="text-blue-200 text-sm">0%</div>
                    @endif
                    <div class="text-blue-200 text-sm">vs periode sebelumnya</div>
                </div>
            </div>
        </div>
 
        <!-- Card Total Penjualan -->
        <div class="relative rounded-xl shadow-sm border border-gray-200 bg-gradient-to-br from-rose-900 to-rose-800">
            <div class="p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-sm text-rose-200 mb-1">Total Penjualan</div>
                        <div class="text-2xl font-semibold text-white">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</div>
                    </div>
                    <div class="rounded-lg bg-rose-800/50 p-3">
                        <i class="fas fa-chart-line text-rose-200"></i>
                    </div>
                </div>
                <div class="flex items-center gap-1 mt-3">
                    @if($persentasePenjualan > 0)
                        <i class="fas fa-arrow-up text-emerald-400 text-sm"></i>
                        <div class="text-emerald-400 text-sm">{{ number_format($persentasePenjualan, 1) }}%</div>
                    @elseif($persentasePenjualan < 0)
                        <i class="fas fa-arrow-down text-red-400 text-sm"></i>
                        <div class="text-red-400 text-sm">{{ number_format(abs($persentasePenjualan), 1) }}%</div>
                    @else
                        <i class="fas fa-minus text-rose-200 text-sm"></i>
                        <div class="text-rose-200 text-sm">0%</div>
                    @endif
                    <div class="text-rose-200 text-sm">vs periode sebelumnya</div>
                </div>
            </div>
        </div>
 
    <!-- Card Total Supplier -->
    <div class="relative rounded-xl shadow-sm border border-gray-200 bg-gradient-to-br from-indigo-900 to-indigo-800">
        <div class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-sm text-indigo-200 mb-1">Total Supplier</div>
                    <div class="text-2xl font-semibold text-white">{{ $totalSupplier }}</div>
                </div>
                <div class="rounded-lg bg-indigo-800/50 p-3">
                    <i class="fas fa-building text-indigo-200"></i>
                </div>
            </div>
            <div class="flex items-center gap-1 mt-3">
                @if($persentaseSupplier > 0)
                    <i class="fas fa-arrow-up text-emerald-400 text-sm"></i>
                    <div class="text-emerald-400 text-sm">{{ number_format($persentaseSupplier, 1) }}%</div>
                @elseif($persentaseSupplier < 0)
                    <i class="fas fa-arrow-down text-red-400 text-sm"></i>
                    <div class="text-red-400 text-sm">{{ number_format(abs($persentaseSupplier), 1) }}%</div>
                @else
                    <i class="fas fa-minus text-indigo-200 text-sm"></i>
                    <div class="text-indigo-200 text-sm">0%</div>
                @endif
                <div class="text-indigo-200 text-sm">vs periode sebelumnya</div>
            </div>
        </div>
    </div>
</div>