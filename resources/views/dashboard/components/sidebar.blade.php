<div class="bg-[#f2f3f5] w-64 fixed left-5 top-5 shadow-xl rounded-xl bottom-5 ">
    <div class="relative">
        <div class="bg-white p-3 flex items-center gap-2 border-b m-2 rounded-xl mt-6 mx-4">
            <div class="bg-blue-500 w-8 h-8 rounded-lg flex items-center justify-center">
                <i class="fas fa-user text-white text-sm"></i>
            </div>
            <div class="min-w-0 flex-1">
                <h1 class="text-xs text-gray-400">Logged in as</h1>
                <h2 class="text-sm font-medium text-gray-800 truncate">{{ Auth::user()->name }}</h2>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="ml-auto text-gray-400 hover:text-red-500 transition-colors" title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>
    
    <nav class="p-4">
        <span class="text-xs font-medium text-gray-400 block mb-3">MENU</span>
        <div class="space-y-3">
            <a href="{{ route('dashboard') }}" 
                class=" px-4 py-2 rounded-lg flex items-center justify-between {{ request()->routeIs('dashboard') ? 'bg-white text-blue-600' : 'text-gray-600 hover:bg-white' }}">
                <div class="flex items-center gap-2">
                    <i class="fas fa-asterisk w-5"></i>
                    <span>Dashboard</span>
                </div> 
                @if(request()->routeIs('dashboard'))
                    <i class="fas fa-chevron-right text-sm"></i>
                @endif
            </a> 
            <a href="{{ route('supplier') }}" 
                class=" px-4 py-2 rounded-lg flex items-center justify-between {{ request()->routeIs('supplier') ? 'bg-white text-blue-600' : 'text-gray-600 hover:bg-white' }}">
                <div class="flex items-center gap-2">
                    <i class="fas fa-user w-5"></i>
                    <span>Supplier</span>
                </div>
                @if(request()->routeIs('supplier'))
                    <i class="fas fa-chevron-right text-sm"></i>
                @endif
            </a> 
            <a href="{{ route('produk.produk') }}" 
                class=" px-4 py-2 rounded-lg flex items-center justify-between {{ request()->routeIs('produk.produk') ? 'bg-white text-blue-600' : 'text-gray-600 hover:bg-white' }}">
                <div class="flex items-center gap-2">
                    <i class="fas fa-box w-5"></i>
                    <span>Produk</span>
                </div>
                @if(request()->routeIs('produk.produk'))
                    <i class="fas fa-chevron-right text-sm"></i>
                @endif
            </a>
            <a href="{{ route('transaksi.index') }}" 
                class=" px-4 py-2 rounded-lg flex items-center justify-between {{ request()->routeIs('transaksi.index') ? 'bg-white text-blue-600' : 'text-gray-600 hover:bg-white' }}">
                <div class="flex items-center gap-2">
                    <i class="fas fa-exchange-alt w-5"></i>
                    <span>Transaksi</span>
                </div>
                @if(request()->routeIs('transaksi.index'))
                    <i class="fas fa-chevron-right text-sm"></i>
                @endif
            </a>
        </div>

        <span class="text-xs font-medium text-gray-400 block mb-3 mt-6">TOOLS</span>
        <div class="space-y-3">
            <a href="{{ route('log-viewer') }}" 
                class=" px-4 py-2 rounded-lg flex items-center justify-between {{ request()->routeIs('log-viewer') ? 'bg-white text-blue-600' : 'text-gray-600 hover:bg-white' }}">
                <div class="flex items-center gap-2">
                    <i class="fas fa-file-alt w-5"></i>
                    <span>Log Viewer</span>
                </div>
                @if(request()->routeIs('log-viewer'))
                    <i class="fas fa-chevron-right text-sm"></i>
                @endif
            </a>
        </div>
    </nav>
</div>
