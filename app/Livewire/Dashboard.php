<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Produk;
use App\Models\Supplier;
use App\Models\Transaksi;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $period = '6m';
    public $totalPenjualan = 0;
    public $persentasePenjualan = 0;
    public $totalProduk = 0;
    public $persentaseProduk = 0;
    public $totalSupplier = 0;
    public $persentaseSupplier = 0;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // Set date ranges based on period
        switch($this->period) {
            case '7d':
                $startDate = now()->subDays(7);
                $previousStartDate = now()->subDays(14);
                break;
            case '30d':
                $startDate = now()->subDays(30);
                $previousStartDate = now()->subDays(60);
                break;
            case '3m':
                $startDate = now()->subMonths(3);
                $previousStartDate = now()->subMonths(6);
                break;
            case '1y':
                $startDate = now()->subYear();
                $previousStartDate = now()->subYears(2);
                break;
            default: // 6m
                $startDate = now()->subMonths(6);
                $previousStartDate = now()->subMonths(12);
        }
        
        $endDate = now();
        $previousEndDate = $startDate;

        // Calculate totals
        $this->totalPenjualan = Transaksi::where('tgl_jual', '>=', $startDate)->sum('total_harga');
        $penjualanSebelumnya = Transaksi::whereBetween('tgl_jual', [$previousStartDate, $previousEndDate])->sum('total_harga');
        
        $this->totalProduk = Produk::where('created_at', '>=', $startDate)->count();
        $produkSebelumnya = Produk::whereBetween('created_at', [$previousStartDate, $previousEndDate])->count();
        
        $this->totalSupplier = Supplier::where('created_at', '>=', $startDate)->count();
        $supplierSebelumnya = Supplier::whereBetween('created_at', [$previousStartDate, $previousEndDate])->count();

        // Calculate percentages
        $this->persentasePenjualan = $penjualanSebelumnya > 0 ? (($this->totalPenjualan - $penjualanSebelumnya) / $penjualanSebelumnya) * 100 : 0;
        $this->persentaseProduk = $produkSebelumnya > 0 ? (($this->totalProduk - $produkSebelumnya) / $produkSebelumnya) * 100 : 0;
        $this->persentaseSupplier = $supplierSebelumnya > 0 ? (($this->totalSupplier - $supplierSebelumnya) / $supplierSebelumnya) * 100 : 0;
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}