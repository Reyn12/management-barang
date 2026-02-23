<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-semibold">Supplier</h1>
        <p class="text-sm text-gray-500">Supplier Data</p>
    </div>
    <div class="flex items-center gap-4">
        <div class="flex items-center gap-2 bg-white px-3 py-2 rounded-lg shadow-sm">
            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                <span class="text-white font-medium">{{ strtoupper(mb_substr(Auth::user()->name ?? 'U', 0, 1)) }}</span>
            </div>
            <span class="text-sm font-medium text-gray-800 truncate max-w-[120px]" title="{{ Auth::user()->name }}">{{ Auth::user()->name }}</span>
        </div>
    </div>
</div>
<hr class="border-gray-300 mb-8">