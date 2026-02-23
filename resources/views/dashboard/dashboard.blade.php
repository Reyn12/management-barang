@extends('master')

@section('title', 'Dashboard')

@section('content')
    <!-- Isi konten disini -->
    <div class="flex p-5 ">
        @include('dashboard.components.sidebar')
        
        <div class="ml-[280px] flex-1 py-4 px-8 bg-white rounded-xl mb-4">
            @include('dashboard.components.header')

            @include('dashboard.components.filter-bar')

            @include('dashboard.components.summary3card')

            @include('dashboard.components.chart-3-card-statusPesanan')
            
            @include('dashboard.components.chart-2-card')
        </div>
    </div>
@endsection

@push('styles')
    <!-- CSS tambahan -->
@endpush

@push('scripts')
    <!-- JavaScript tambahan -->
@endpush