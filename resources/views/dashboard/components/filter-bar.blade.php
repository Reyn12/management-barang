<div class="flex items-center justify-between">
    <div class="flex items-center gap-4">
        <form action="{{ route('dashboard') }}" method="GET" class="flex items-center gap-2 flex-wrap">
            <div class="flex items-center gap-2">
                <i class="far fa-calendar text-gray-500"></i>
                <label class="text-sm text-gray-600">Dari</label>
                <input type="date" name="date_from" value="{{ request('date_from', now()->subMonths(6)->format('Y-m-d')) }}"
                    class="border border-gray-300 px-3 py-2 rounded-lg text-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>
            <div class="flex items-center gap-2">
                <label class="text-sm text-gray-600">Sampai</label>
                <input type="date" name="date_to" value="{{ request('date_to', now()->format('Y-m-d')) }}"
                    class="border border-gray-300 px-3 py-2 rounded-lg text-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>
            <button type="submit" class="flex items-center gap-2 border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm">
                <i class="fas fa-filter"></i>
                <span>Terapkan</span>
            </button>
        </form>
    </div>
    <div class="flex items-center gap-4">
        <button onclick="refreshPage(this)" class="flex items-center gap-2 border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-50">
            <i class="fas fa-sync-alt"></i>
            <span>Refresh</span>
        </button>
        <div x-data="{ showDownloadModal: false }">
            <button @click="showDownloadModal = true" 
                class="flex items-center gap-2 bg-gradient-to-r from-blue-700 to-blue-900 text-white px-6 py-2 rounded-lg hover:from-blue-800 hover:to-blue-950">
                <i class="fas fa-download"></i>
                <span>Download</span>
            </button>

            <!-- Modal Download Options -->
            <div x-show="showDownloadModal" 
                class="fixed inset-0 z-50 overflow-y-auto"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                x-cloak>
                
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-black bg-opacity-50"></div>

                <!-- Modal content -->
                <div class="relative min-h-screen flex items-center justify-center p-4">
                    <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full">
                        <!-- Header -->
                        <div class="py-4 px-6 border-b">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">Download Data</h3>
                                <button @click="showDownloadModal = false" class="text-gray-400 hover:text-gray-500">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Body -->
                        <div class="p-6">
                            <div class="grid grid-cols-3 gap-6">
                                <!-- Supplier Download -->
                                <div class="flex flex-col items-center p-6 border-2 border-blue-200 rounded-xl hover:bg-blue-50 transition-colors cursor-pointer"
                                    onclick="downloadBoth('supplier')">
                                    <i class="fas fa-users text-4xl text-blue-500 mb-3"></i>
                                    <span class="text-sm font-medium text-gray-700">Data Supplier</span>
                                    <span class="text-xs text-gray-500 mt-2">Download PDF & Excel</span>
                                </div>

                                <!-- Produk Download -->
                                <div class="flex flex-col items-center p-6 border-2 border-purple-200 rounded-xl hover:bg-purple-50 transition-colors cursor-pointer"
                                    onclick="downloadBoth('produk')">
                                    <i class="fas fa-box text-4xl text-purple-500 mb-3"></i>
                                    <span class="text-sm font-medium text-gray-700">Data Produk</span>
                                    <span class="text-xs text-gray-500 mt-2">Download PDF & Excel</span>
                                </div>

                                <!-- Transaksi Download -->
                                <div class="flex flex-col items-center p-6 border-2 border-orange-200 rounded-xl hover:bg-orange-50 transition-colors cursor-pointer"
                                    onclick="downloadBoth('transaksi')">
                                    <i class="fas fa-exchange-alt text-4xl text-orange-500 mb-3"></i>
                                    <span class="text-sm font-medium text-gray-700">Data Transaksi</span>
                                    <span class="text-xs text-gray-500 mt-2">Download PDF & Excel</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function refreshPage(button) {
    const icon = button.querySelector('i');
    icon.classList.add('animate-spin');
    button.disabled = true;
    setTimeout(() => {
        window.location.reload();
    }, 500);
}

function showCustomizeAlert() {
    Swal.fire({
        title: 'Coming Soon!',
        text: 'Customize feature will be available soon',
        icon: 'info',
        confirmButtonText: 'OK',
        confirmButtonColor: '#3085d6',
        showClass: {
            popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
        }
    });
}

function downloadBoth(type) {
    // Download PDF
    const pdfUrl = `{{ url('/${type}/download/pdf') }}`;
    const excelUrl = `{{ url('/${type}/download/excel') }}`;
    
    // Download PDF dulu
    fetch(pdfUrl)
        .then(response => response.blob())
        .then(blob => {
            const link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = `${type}_data.pdf`;
            link.click();
            
            // Setelah PDF selesai, download Excel
            setTimeout(() => {
                fetch(excelUrl)
                    .then(response => response.blob())
                    .then(blob => {
                        const link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = `${type}_data.xlsx`;
                        link.click();
                    });
            }, 1000); // Delay 1 detik
        });
}
</script>