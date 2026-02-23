@extends('master')

@section('title', 'About & Tools')

@section('content')
<div class="flex p-5">
    @include('dashboard.components.sidebar')

    <div class="ml-[280px] flex-1 py-4 px-8 bg-white rounded-xl mb-4">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-semibold flex items-center gap-3">
                    <i class="fas fa-info-circle text-blue-500"></i> About & Tools
                </h1>
                <p class="text-sm text-gray-500 mt-1">Informasi versi & environment aplikasi</p>
            </div>
            <div class="flex items-center gap-2 bg-gray-50 px-3 py-2 rounded-lg shadow-sm border border-gray-100">
                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                    <span class="text-white font-medium text-sm">{{ strtoupper(mb_substr(Auth::user()->name ?? 'U', 0, 1)) }}</span>
                </div>
                <span class="text-sm font-medium text-gray-800 truncate max-w-[120px]" title="{{ Auth::user()->name }}">{{ Auth::user()->name }}</span>
            </div>
        </div>
        <hr class="border-gray-200 mb-8">

        {{-- Cards Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($tools as $tool)
            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow">
                <span class="font-semibold text-gray-800 block mb-2">{{ $tool['name'] }}</span>
                <p class="text-lg font-mono font-medium text-gray-900 truncate" title="{{ $tool['value'] }}">{{ $tool['value'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
