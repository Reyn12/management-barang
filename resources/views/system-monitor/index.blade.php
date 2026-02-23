{{-- J.620100.045.01 - Pemantauan Resource Sistem --}}
@extends('master')

@section('title', 'System Monitor')

@section('content')
<div class="flex p-5">
    @include('dashboard.components.sidebar')

    <div class="ml-[280px] flex-1 py-4 px-8 bg-white rounded-xl mb-4">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-semibold flex items-center gap-3">
                    <i class="fas fa-heartbeat text-rose-500 "></i> System Monitor
                </h1>
                <p class="text-sm text-gray-500 mt-1">Monitoring performa, database & konfigurasi server</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('system-monitor') }}" class="flex items-center gap-2 border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm transition">
                    <i class="fas fa-sync-alt"></i> Refresh
                </a>
                <div class="flex items-center gap-2 bg-gray-50 px-3 py-2 rounded-lg shadow-sm border border-gray-100">
                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                        <span class="text-white font-medium text-sm">{{ strtoupper(mb_substr(Auth::user()->name ?? 'U', 0, 1)) }}</span>
                    </div>
                    <span class="text-sm font-medium text-gray-800 truncate max-w-[120px]">{{ Auth::user()->name }}</span>
                </div>
            </div>
        </div>

        {{-- Performance Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
            {{-- Response Time --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-2xl p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-bolt text-white"></i>
                    </div>
                    <span class="text-xs font-medium text-blue-600 uppercase tracking-wide">Response</span>
                </div>
                <div class="text-2xl font-bold text-gray-900">{{ $responseTime }} <span class="text-sm font-normal text-gray-500">ms</span></div>
                <div class="absolute -bottom-3 -right-3 text-blue-200 opacity-30"><i class="fas fa-bolt text-6xl"></i></div>
            </div>

            {{-- Memory Used --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-emerald-50 to-emerald-100 border border-emerald-200 rounded-2xl p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-memory text-white"></i>
                    </div>
                    <span class="text-xs font-medium text-emerald-600 uppercase tracking-wide">Memory</span>
                </div>
                <div class="text-2xl font-bold text-gray-900">{{ number_format($memoryUsed / 1048576, 1) }} <span class="text-sm font-normal text-gray-500">MB</span></div>
                <div class="absolute -bottom-3 -right-3 text-emerald-200 opacity-30"><i class="fas fa-memory text-6xl"></i></div>
            </div>

            {{-- Peak Memory --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-amber-50 to-amber-100 border border-amber-200 rounded-2xl p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-amber-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-area text-white"></i>
                    </div>
                    <span class="text-xs font-medium text-amber-600 uppercase tracking-wide">Peak Mem</span>
                </div>
                <div class="text-2xl font-bold text-gray-900">{{ number_format($peakMemory / 1048576, 1) }} <span class="text-sm font-normal text-gray-500">MB</span></div>
                <div class="absolute -bottom-3 -right-3 text-amber-200 opacity-30"><i class="fas fa-chart-area text-6xl"></i></div>
            </div>

            {{-- DB Size --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-rose-50 to-rose-100 border border-rose-200 rounded-2xl p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-rose-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-database text-white"></i>
                    </div>
                    <span class="text-xs font-medium text-rose-600 uppercase tracking-wide">DB ({{ $dbInfo['driver'] }})</span>
                </div>
                <div class="text-2xl font-bold text-gray-900">
                    @if($dbInfo['size'] >= 1048576)
                        {{ number_format($dbInfo['size'] / 1048576, 1) }} <span class="text-sm font-normal text-gray-500">MB</span>
                    @else
                        {{ number_format($dbInfo['size'] / 1024, 1) }} <span class="text-sm font-normal text-gray-500">KB</span>
                    @endif
                </div>
                <div class="absolute -bottom-3 -right-3 text-rose-200 opacity-30"><i class="fas fa-database text-6xl"></i></div>
            </div>

            {{-- Storage Used --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-violet-50 to-violet-100 border border-violet-200 rounded-2xl p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-violet-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-hdd text-white"></i>
                    </div>
                    <span class="text-xs font-medium text-violet-600 uppercase tracking-wide">Storage</span>
                </div>
                <div class="text-2xl font-bold text-gray-900">
                    @if($storageUsed >= 1048576)
                        {{ number_format($storageUsed / 1048576, 1) }} <span class="text-sm font-normal text-gray-500">MB</span>
                    @else
                        {{ number_format($storageUsed / 1024, 1) }} <span class="text-sm font-normal text-gray-500">KB</span>
                    @endif
                </div>
                <div class="absolute -bottom-3 -right-3 text-violet-200 opacity-30"><i class="fas fa-hdd text-6xl"></i></div>
            </div>
        </div>

        {{-- Bottom Grid: DB Stats + PHP Config + Server Info --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Statistik Database --}}
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-800">
                    <h2 class="text-white font-semibold flex items-center gap-2">
                        <i class="fas fa-table"></i> Statistik Database
                    </h2>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach($tables as $table)
                    <div class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition">
                        <div class="flex items-center gap-3">
                            <i class="fas {{ $table['icon'] }} {{ $table['color'] }} w-5 text-center"></i>
                            <span class="text-sm font-medium text-gray-700">{{ $table['name'] }}</span>
                        </div>
                        <span class="bg-gray-100 text-gray-800 text-sm font-semibold px-3 py-1 rounded-full">{{ number_format($table['count']) }}</span>
                    </div>
                    @endforeach
                    <div class="flex items-center justify-between px-6 py-4 bg-gray-50">
                        <span class="text-sm font-bold text-gray-800">Total Record</span>
                        <span class="bg-blue-600 text-white text-sm font-semibold px-3 py-1 rounded-full">{{ number_format(collect($tables)->sum('count')) }}</span>
                    </div>
                </div>
            </div>

            {{-- Konfigurasi PHP --}}
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-indigo-800">
                    <h2 class="text-white font-semibold flex items-center gap-2">
                        <i class="fab fa-php"></i> Konfigurasi PHP
                    </h2>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach($phpConfig as $config)
                    <div class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition">
                        <span class="text-sm text-gray-600">{{ $config['label'] }}</span>
                        <span class="text-sm font-semibold {{ $config['color'] }}">{{ $config['value'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Server Info --}}
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-slate-600 to-slate-800">
                    <h2 class="text-white font-semibold flex items-center gap-2">
                        <i class="fas fa-server"></i> Server Info
                    </h2>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach($serverInfo as $info)
                    <div class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition">
                        <span class="text-sm text-gray-600">{{ $info['label'] }}</span>
                        <span class="text-sm font-mono text-gray-800 max-w-[200px] truncate" title="{{ $info['value'] }}">{{ $info['value'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
