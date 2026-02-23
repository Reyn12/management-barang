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

        /*
        |--------------------------------------------------------------------------
        | SQL Manual Query
        |--------------------------------------------------------------------------
        | GET /?date_from=2026-01-01&date_to=2026-02-23
        |
        | - date_from: tanggal mulai (Y-m-d)
        | - date_to  : tanggal akhir (Y-m-d)
        |
        | Catatan:
        | - Kalau param kosong, default: 6 bulan terakhir s/d hari ini.
        | - Beberapa data pakai tgl_jual (transaksi), beberapa pakai created_at (produk/supplier).
        */

        // Custom date range: default 6 bulan lalu s/d hari ini
        $dateFrom = $request->filled('date_from')
            ? Carbon::parse($request->date_from)->startOfDay()
            : now()->subMonths(6)->startOfDay();
        $dateTo = $request->filled('date_to')
            ? Carbon::parse($request->date_to)->endOfDay()
            : now()->endOfDay();

        /*
        | SQL Manual Query
        | SELECT kategori, COUNT(*) AS total FROM produks GROUP BY kategori;
        */
        $kategoriData = Produk::select('kategori', DB::raw('count(*) as total'))
                     ->groupBy('kategori')
                     ->get();

        $labels = $kategoriData->pluck('kategori')->toArray();
        $series = $kategoriData->pluck('total')->toArray();
        // Get top selling products
        /*
        | SQL Manual Query
        | SELECT p.nama_produk, SUM(t.jumlah) AS total_terjual
        | FROM transaksis t
        | JOIN produks p ON t.id_produk = p.id_produk
        | GROUP BY p.id_produk, p.nama_produk
        | ORDER BY total_terjual DESC
        | LIMIT 5;
        */
        $topProducts = Transaksi::select('produks.nama_produk', DB::raw('SUM(transaksis.jumlah) as total_terjual'))
            ->join('produks', 'transaksis.id_produk', '=', 'produks.id_produk')
            ->groupBy('produks.id_produk', 'produks.nama_produk')
            ->orderBy('total_terjual', 'desc')
            ->take(5)
            ->get();

        $productLabels = $topProducts->pluck('nama_produk')->toArray();
        $productSeries = $topProducts->pluck('total_terjual')->toArray();

        // Get lowest stock products
        /*
        | SQL Manual Query
        | SELECT nama_produk, stok FROM produks ORDER BY stok ASC LIMIT 5;
        */
        $lowStockProducts = Produk::orderBy('stok', 'asc')
            ->take(5)
            ->get(['nama_produk', 'stok']);

        $stockLabels = $lowStockProducts->pluck('nama_produk')->toArray();
        $stockSeries = $lowStockProducts->pluck('stok')->toArray();

        // Get top suppliers by product count
        /*
        | SQL Manual Query
        | SELECT s.nama_supplier, COUNT(p.id_produk) AS total_produk
        | FROM suppliers s
        | LEFT JOIN produks p ON s.id_supplier = p.id_supplier
        | GROUP BY s.id_supplier, s.nama_supplier
        | ORDER BY total_produk DESC
        | LIMIT 5;
        */
        $topSuppliers = Supplier::select('suppliers.nama_supplier', DB::raw('COUNT(produks.id_produk) as total_produk'))
            ->leftJoin('produks', 'suppliers.id_supplier', '=', 'produks.id_supplier')
            ->groupBy('suppliers.id_supplier', 'suppliers.nama_supplier')
            ->orderBy('total_produk', 'desc')
            ->take(5)
            ->get();

        $supplierLabels = $topSuppliers->pluck('nama_supplier')->toArray();
        $supplierSeries = $topSuppliers->pluck('total_produk')->toArray();

        // Get stock and sales by category
        /*
        | SQL Manual Query
        | SELECT p.kategori,
        |        SUM(p.stok) AS total_stok,
        |        COALESCE(SUM(t.jumlah), 0) AS total_terjual
        | FROM produks p
        | LEFT JOIN transaksis t ON p.id_produk = t.id_produk
        | GROUP BY p.kategori;
        */
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
        /*
        | SQL Manual Query
        | SELECT SUM(total_harga) FROM transaksis
        | WHERE tgl_jual BETWEEN :start AND :end;
        */
        $totalPenjualan = Transaksi::whereBetween('tgl_jual', [$startDate, $endDate])
                            ->sum('total_harga');
        /*
        | SQL Manual Query
        | SELECT SUM(total_harga) FROM transaksis
        | WHERE tgl_jual BETWEEN :prev_start AND :prev_end;
        */
        $penjualanSebelumnya = Transaksi::whereBetween('tgl_jual', [
            $previousStartDate,
            $previousEndDate
        ])->sum('total_harga');

        $persentasePenjualan = 0;
        if ($penjualanSebelumnya > 0) {
            $persentasePenjualan = (($totalPenjualan - $penjualanSebelumnya) / $penjualanSebelumnya) * 100;
        }

        // Total Produk + Persentase
        /*
        | SQL Manual Query
        | SELECT COUNT(*) FROM produks
        | WHERE created_at BETWEEN :start AND :end;
        */
        $totalProduk = Produk::whereBetween('created_at', [$startDate, $endDate])->count();
        /*
        | SQL Manual Query
        | SELECT COUNT(*) FROM produks
        | WHERE created_at BETWEEN :prev_start AND :prev_end;
        */
        $produkSebelumnya = Produk::whereBetween('created_at', [
            $previousStartDate,
            $previousEndDate
        ])->count();

        $persentaseProduk = 0;
        if ($produkSebelumnya > 0) {
            $persentaseProduk = (($totalProduk - $produkSebelumnya) / $produkSebelumnya) * 100;
        }

        // Total Supplier + Persentase
        /*
        | SQL Manual Query
        | SELECT COUNT(*) FROM suppliers
        | WHERE created_at BETWEEN :start AND :end;
        */
        $totalSupplier = Supplier::whereBetween('created_at', [$startDate, $endDate])->count();
        /*
        | SQL Manual Query
        | SELECT COUNT(*) FROM suppliers
        | WHERE created_at BETWEEN :prev_start AND :prev_end;
        */
        $supplierSebelumnya = Supplier::whereBetween('created_at', [
            $previousStartDate,
            $previousEndDate
        ])->count();

        $persentaseSupplier = 0;
        if ($supplierSebelumnya > 0) {
            $persentaseSupplier = (($totalSupplier - $supplierSebelumnya) / $supplierSebelumnya) * 100;
        }

        // Hitung jumlah transaksi per status (Belum Bayar | Sudah Bayar)
        /*
        | SQL Manual Query
        | SELECT COUNT(*) FROM transaksis
        | WHERE status_bayar = 'Belum Bayar' AND tgl_jual BETWEEN :start AND :end;
        |
        | SELECT COUNT(*) FROM transaksis
        | WHERE status_bayar = 'Sudah Bayar' AND tgl_jual BETWEEN :start AND :end;
        */
        $belumBayarCount = Transaksi::where('status_bayar', 'Belum Bayar')
            ->whereBetween('tgl_jual', [$startDate, $endDate])
            ->count();
        $sudahBayarCount = Transaksi::where('status_bayar', 'Sudah Bayar')
            ->whereBetween('tgl_jual', [$startDate, $endDate])
            ->count();

        /*
        | SQL Manual Query
        | SELECT SUM(total_harga) FROM transaksis
        | WHERE status_bayar = 'Belum Bayar' AND tgl_jual BETWEEN :start AND :end;
        |
        | SELECT SUM(total_harga) FROM transaksis
        | WHERE status_bayar = 'Sudah Bayar' AND tgl_jual BETWEEN :start AND :end;
        */
        $belumBayarTotal = Transaksi::where('status_bayar', 'Belum Bayar')
            ->whereBetween('tgl_jual', [$startDate, $endDate])
            ->sum('total_harga');
        $sudahBayarTotal = Transaksi::where('status_bayar', 'Sudah Bayar')
            ->whereBetween('tgl_jual', [$startDate, $endDate])
            ->sum('total_harga');

        // Data untuk chart 7 hari terakhir
        /*
        | SQL Manual Query
        | SELECT COUNT(*) FROM transaksis
        | WHERE status_bayar = 'Belum Bayar' AND DATE(created_at) = :date;
        |
        | SELECT COUNT(*) FROM transaksis
        | WHERE status_bayar = 'Sudah Bayar' AND DATE(created_at) = :date;
        */
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