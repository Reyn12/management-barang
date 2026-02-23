<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Supplier;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {

        // Get period from request, default to 6m
        $period = $request->get('period', '6m');
        $kategoriData = Produk::select('kategori', DB::raw('count(*) as total'))
                     ->groupBy('kategori')
                     ->get();

        $labels = $kategoriData->pluck('kategori')->toArray();
        $series = $kategoriData->pluck('total')->toArray(); // Tambahkan ini

        // Get top selling products
        $topProducts = Transaksi::select('produks.nama_produk', DB::raw('SUM(transaksis.jumlah) as total_terjual'))
            ->join('produks', 'transaksis.id_produk', '=', 'produks.id_produk')
            ->groupBy('produks.id_produk', 'produks.nama_produk')
            ->orderBy('total_terjual', 'desc')
            ->take(5)
            ->get();

        $productLabels = $topProducts->pluck('nama_produk')->toArray();
        $productSeries = $topProducts->pluck('total_terjual')->toArray();

        // Get lowest stock products
        $lowStockProducts = Produk::orderBy('stok', 'asc')
            ->take(5)
            ->get(['nama_produk', 'stok']);

        $stockLabels = $lowStockProducts->pluck('nama_produk')->toArray();
        $stockSeries = $lowStockProducts->pluck('stok')->toArray();

        // Get top suppliers by product count
        $topSuppliers = Supplier::select('suppliers.nama_supplier', DB::raw('COUNT(produks.id_produk) as total_produk'))
            ->leftJoin('produks', 'suppliers.id_supplier', '=', 'produks.id_supplier')
            ->groupBy('suppliers.id_supplier', 'suppliers.nama_supplier')
            ->orderBy('total_produk', 'desc')
            ->take(5)
            ->get();

        $supplierLabels = $topSuppliers->pluck('nama_supplier')->toArray();
        $supplierSeries = $topSuppliers->pluck('total_produk')->toArray();

        // Get stock and sales by category
        $categoryStats = DB::table('produks')
        ->select(
            'produks.kategori',
            DB::raw('SUM(produks.stok) as total_stok'),
            DB::raw('COALESCE(SUM(transaksis.jumlah), 0) as total_terjual')
        )
        ->leftJoin('transaksis', 'produks.id_produk', '=', 'transaksis.id_produk')
        ->groupBy('produks.kategori')
        ->get();

        $categories = $categoryStats->pluck('kategori')->toArray();
        $stockData = $categoryStats->pluck('total_stok')->toArray();
        $salesData = $categoryStats->pluck('total_terjual')->toArray();
        
        // Set date ranges based on period
        switch($period) {
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

        // Total Penjualan + Persentase
        $totalPenjualan = Transaksi::where('tgl_jual', '>=', $startDate)
                            ->sum('total_harga');
        $penjualanSebelumnya = Transaksi::whereBetween('tgl_jual', [
            $previousStartDate,
            $previousEndDate
        ])->sum('total_harga');

        $persentasePenjualan = 0;
        if ($penjualanSebelumnya > 0) {
            $persentasePenjualan = (($totalPenjualan - $penjualanSebelumnya) / $penjualanSebelumnya) * 100;
        }

        // Total Produk + Persentase
        $totalProduk = Produk::where('created_at', '>=', $startDate)->count();
        $produkSebelumnya = Produk::whereBetween('created_at', [
            $previousStartDate,
            $previousEndDate
        ])->count();

        $persentaseProduk = 0;
        if ($produkSebelumnya > 0) {
            $persentaseProduk = (($totalProduk - $produkSebelumnya) / $produkSebelumnya) * 100;
        }

        // Total Supplier + Persentase
        $totalSupplier = Supplier::where('created_at', '>=', $startDate)->count();
        $supplierSebelumnya = Supplier::whereBetween('created_at', [
            $previousStartDate,
            $previousEndDate
        ])->count();

        $persentaseSupplier = 0;
        if ($supplierSebelumnya > 0) {
            $persentaseSupplier = (($totalSupplier - $supplierSebelumnya) / $supplierSebelumnya) * 100;
        }

        // Hitung jumlah transaksi per status
        $pendingCount = Transaksi::where('status_bayar', 'Pending')
            ->where('tgl_jual', '>=', $startDate)
            ->count();
        $suksesCount = Transaksi::where('status_bayar', 'Sukses')
            ->where('tgl_jual', '>=', $startDate)
            ->count();
        $gagalCount = Transaksi::where('status_bayar', 'Gagal')
            ->where('tgl_jual', '>=', $startDate)
            ->count();
        
        $pendingTotal = Transaksi::where('status_bayar', 'Pending')
            ->where('tgl_jual', '>=', $startDate)
            ->sum('total_harga');
        $suksesTotal = Transaksi::where('status_bayar', 'Sukses')
            ->where('tgl_jual', '>=', $startDate)
            ->sum('total_harga');
        $gagalTotal = Transaksi::where('status_bayar', 'Gagal')
            ->where('tgl_jual', '>=', $startDate)
            ->sum('total_harga');        
        // Data untuk chart 7 hari terakhir
        $dates = collect(range(6, 0))->map(function ($days) {
            return Carbon::now()->subDays($days)->format('Y-m-d');
        });

        $pendingSeries = $dates->map(function ($date) {
            return Transaksi::where('status_bayar', 'Pending')
                ->whereDate('created_at', $date)
                ->count();
        })->values();

        $suksesSeries = $dates->map(function ($date) {
            return Transaksi::where('status_bayar', 'Sukses')
                ->whereDate('created_at', $date)
                ->count();
        })->values();

        $gagalSeries = $dates->map(function ($date) {
            return Transaksi::where('status_bayar', 'Gagal')
                ->whereDate('created_at', $date)
                ->count();
        })->values();

        $dates = $dates->map(function ($date) {
            return Carbon::parse($date)->format('d M');
        });

        return view('dashboard.dashboard', compact(
            'totalProduk',
            'persentaseProduk',
            'totalSupplier',
            'persentaseSupplier',
            'totalPenjualan',
            'persentasePenjualan',
            'labels',
            'series',
            'stockLabels',
            'stockSeries',
            'productLabels',
            'productSeries',
            'supplierLabels',
            'supplierSeries',
            'categories',
            'stockData',
            'salesData',
            'pendingCount',
            'suksesCount',
            'gagalCount',
            'pendingSeries',
            'suksesSeries',
            'gagalSeries',
            'dates',
            'pendingTotal',
            'suksesTotal',
            'gagalTotal'
        ));
    }
}