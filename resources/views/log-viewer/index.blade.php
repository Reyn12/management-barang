@extends('master')

@section('title', 'Log Viewer')

@section('content')
<div class="flex p-5">
    @include('dashboard.components.sidebar')

    <div class="ml-[280px] flex-1 py-4 px-8 bg-white rounded-xl mb-4">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-semibold flex items-center gap-3">
                    <i class="fas fa-file-alt text-blue-500"></i> Log Viewer
                </h1>
                @if($logDates)
                    <p class="text-sm text-gray-500 mt-1">{{ $logDates }}</p>
                @endif
            </div>
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 bg-blue-100 text-blue-700 text-sm font-medium rounded-lg">
                    {{ $totalEntries }} entri ditampilkan
                </span>
                <div class="flex items-center gap-2 bg-gray-50 px-3 py-2 rounded-lg shadow-sm border border-gray-100">
                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                        <span class="text-white font-medium text-sm">{{ strtoupper(mb_substr(Auth::user()->name ?? 'U', 0, 1)) }}</span>
                    </div>
                    <span class="text-sm font-medium text-gray-800 truncate max-w-[120px]" title="{{ Auth::user()->name }}">{{ Auth::user()->name }}</span>
                </div>
            </div>
        </div>
        <hr class="border-gray-200 mb-6">

        {{-- Filter Bar --}}
        <form method="GET" action="{{ route('log-viewer') }}" class="flex flex-wrap items-center gap-3 mb-6">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari teks di log..."
                class="flex-1 min-w-[200px] px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
            >
            <select
                name="level"
                class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
            >
                <option value="">-- Semua Level --</option>
                @foreach(['DEBUG','INFO','NOTICE','WARNING','ERROR','CRITICAL','ALERT','EMERGENCY'] as $lvl)
                    <option value="{{ $lvl }}" {{ request('level') === $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-5 py-2 bg-blue-500 text-white text-sm font-medium rounded-lg hover:bg-blue-600 transition-colors">
                <i class="fas fa-search mr-1"></i> Filter
            </button>
            <a href="{{ route('log-viewer') }}" class="px-4 py-2 bg-gray-100 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                Reset
            </a>
        </form>

        {{-- Log Area --}}
        <div class="bg-gray-900 rounded-xl overflow-hidden">
            <div class="overflow-x-auto overflow-y-auto max-h-[65vh] p-4 font-mono text-sm leading-6">
                @forelse($logs as $log)
                    <div class="flex gap-3 hover:bg-white/5 px-2 py-0.5 rounded">
                        <span class="text-gray-600 select-none shrink-0 w-8 text-right">{{ $log['index'] }}</span>
                        @php
                            $color = match($log['level']) {
                                'DEBUG' => 'text-gray-400',
                                'INFO' => 'text-blue-400',
                                'NOTICE' => 'text-cyan-400',
                                'WARNING' => 'text-yellow-400',
                                'ERROR' => 'text-red-400',
                                'CRITICAL' => 'text-red-500 font-bold',
                                'ALERT' => 'text-orange-400',
                                'EMERGENCY' => 'text-red-300 font-bold',
                                default => 'text-gray-300',
                            };
                        @endphp
                        <div class="flex-1 min-w-0">
                            <span class="text-gray-500">[{{ $log['date'] }}]</span>
                            <span class="text-gray-500">{{ $log['env'] }}.</span><span class="{{ $color }}">{{ $log['level'] }}</span><span class="text-gray-500">:</span>
                            <span class="text-gray-300 whitespace-pre-wrap break-all">{{ $log['message'] }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-gray-500 text-center py-12">
                        @if(request('level') || request('search'))
                            Tidak ada log yang cocok dengan filter.
                        @else
                            Belum ada log.
                        @endif
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
