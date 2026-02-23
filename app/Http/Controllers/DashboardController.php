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

        // Custom date range: default 6 bulan lalu s/d hari ini
        $dateFrom = $request->filled('date_from')
            ? Carbon::parse($request->date_from)->startOfDay()
            : now()->subMonths(6)->startOfDay();
        $dateTo = $request->filled('date_to')
            ? Carbon::parse($request->date_to)->endOfDay()
            : now()->endOfDay();
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
        
        // Periode sebelumnya untuk hitung persentase (same length sebelum dateFrom)
        $rangeDays = $dateFrom->diffInDays($dateTo);
        $previousEndDate = $dateFrom->copy()->subDay();
        $previousStartDate = $previousEndDate->copy()->subDays($rangeDays);
        $startDate = $dateFrom;
        $endDate = $dateTo;

        // Total Penjualan + Persentase
        $totalPenjualan = Transaksi::whereBetween('tgl_jual', [$startDate, $endDate])
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
        $totalProduk = Produk::whereBetween('created_at', [$startDate, $endDate])->count();
        $produkSebelumnya = Produk::whereBetween('created_at', [
            $previousStartDate,
            $previousEndDate
        ])->count();

        $persentaseProduk = 0;
        if ($produkSebelumnya > 0) {
            $persentaseProduk = (($totalProduk - $produkSebelumnya) / $produkSebelumnya) * 100;
        }

        // Total Supplier + Persentase
        $totalSupplier = Supplier::whereBetween('created_at', [$startDate, $endDate])->count();
        $supplierSebelumnya = Supplier::whereBetween('created_at', [
            $previousStartDate,
            $previousEndDate
        ])->count();

        $persentaseSupplier = 0;
        if ($supplierSebelumnya > 0) {
            $persentaseSupplier = (($totalSupplier - $supplierSebelumnya) / $supplierSebelumnya) * 100;
        }

        // Hitung jumlah transaksi per status (Belum Bayar | Sudah Bayar)
        $belumBayarCount = Transaksi::where('status_bayar', 'Belum Bayar')
            ->whereBetween('tgl_jual', [$startDate, $endDate])
            ->count();
        $sudahBayarCount = Transaksi::where('status_bayar', 'Sudah Bayar')
            ->whereBetween('tgl_jual', [$startDate, $endDate])
            ->count();

        $belumBayarTotal = Transaksi::where('status_bayar', 'Belum Bayar')
            ->whereBetween('tgl_jual', [$startDate, $endDate])
            ->sum('total_harga');
        $sudahBayarTotal = Transaksi::where('status_bayar', 'Sudah Bayar')
            ->whereBetween('tgl_jual', [$startDate, $endDate])
            ->sum('total_harga');

        // Data untuk chart 7 hari terakhir
        $dates = collect(range(6, 0))->map(function ($days) {
            return Carbon::now()->subDays($days)->format('Y-m-d');
        });

        $belumBayarSeries = $dates->map(function ($date) {
            return Transaksi::where('status_bayar', 'Belum Bayar')
                ->whereDate('created_at', $date)
                ->count();
        })->values();

        $sudahBayarSeries = $dates->map(function ($date) {
            return Transaksi::where('status_bayar', 'Sudah Bayar')
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
            'belumBayarCount',
            'sudahBayarCount',
            'belumBayarSeries',
            'sudahBayarSeries',
            'dates',
            'belumBayarTotal',
            'sudahBayarTotal'
        ));
    }
}