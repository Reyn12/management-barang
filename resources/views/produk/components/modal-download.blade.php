<div x-show="showDownloadModal" 
    class="fixed inset-0 z-50 overflow-y-auto"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" x-cloak>
    
    <!-- Background overlay -->
    <div class="fixed inset-0 bg-black bg-opacity-50"></div>

    <!-- Modal content -->
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-xl max-w-xl w-full">
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
                <div class="grid grid-cols-2 gap-6">
                    <!-- PDF Download -->
                    <button @click="downloadPDF()" 
                        class="flex flex-col items-center justify-center p-6 border-2 border-red-200 rounded-xl hover:bg-red-50 transition-colors group">
                        <i class="fas fa-file-pdf text-4xl text-red-500 mb-3 group-hover:scale-110 transition-transform"></i>
                        <span class="text-sm font-medium text-gray-700">Download PDF</span>
                    </button>
                    
                    <!-- Excel Download -->
                    <button @click="downloadExcel()" 
                        class="flex flex-col items-center justify-center p-6 border-2 border-green-200 rounded-xl hover:bg-green-50 transition-colors group">
                        <i class="fas fa-file-excel text-4xl text-green-500 mb-3 group-hover:scale-110 transition-transform"></i>
                        <span class="text-sm font-medium text-gray-700">Download Excel</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>