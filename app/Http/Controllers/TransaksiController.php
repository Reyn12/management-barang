<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Exports\TransaksiExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;




class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | SQL Manual Query
        |--------------------------------------------------------------------------
        | GET /transaksi?search=asus&date=month&status=Belum+Bayar
        |
        | SELECT t.*
        | FROM transaksis t
        | LEFT JOIN produks p ON t.id_produk = p.id_produk
        | WHERE
        |   (:search IS NULL OR (
        |       p.nama_produk   LIKE '%:search%' OR
        |       t.id_transaksi  LIKE '%:search%' OR
        |       t.nama_customer LIKE '%:search%'
        |   ))
        |   AND (
        |       :date IS NULL
        |       OR (:date = 'today' AND DATE(t.tgl_jual) = CURRENT_DATE)
        |       OR (:date = 'week'  AND t.tgl_jual BETWEEN start_of_week() AND end_of_week())
        |       OR (:date = 'month' AND MONTH(t.tgl_jual) = MONTH(CURRENT_DATE)
        |                            AND YEAR(t.tgl_jual)  = YEAR(CURRENT_DATE))
        |   )
        |   AND (:status IS NULL OR :status = 'all' OR t.status_bayar = :status)
        | ORDER BY t.id_transaksi DESC
        | LIMIT 10 OFFSET (:page-1)*10;
        */

        // Di awal function, sebelum filter
        Log::info('All Transactions:', [
            'data' => Transaksi::all(['id_transaksi', 'tgl_jual', 'status_bayar'])->toArray()
        ]);

        // Tambahkan logging untuk request
        Log::info('Filter Parameters:', [
            'search' => $request->input('search'),
            'date' => $request->input('date'),
            'status' => $request->input('status')
        ]);

        $search = $request->input('search');
        $date = $request->input('date');
        $status = $request->input('status');
        
        $query = Transaksi::query();
        
        // Search filter
        if ($search) {
            $query->whereHas('produk', function($q) use ($search) {
                $q->where('nama_produk', 'like', "%{$search}%");
            })
            ->orWhere('id_transaksi', 'like', "%{$search}%")
            ->orWhere('nama_customer', 'like', "%{$search}%");
        }

        // Date filter
        if ($date) {
            Log::info('Applying date filter:', ['date' => $date]);
            switch($date) {
                case 'today':
                    $query->whereDate('tgl_jual', today());
                    break;
                case 'week':
                    $query->whereBetween('tgl_jual', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('tgl_jual', now()->month)
                        ->whereYear('tgl_jual', now()->year);
                    break;
            }
        }

        // Status filter
        if ($status && $status !== 'all') {
            Log::info('Applying status filter:', ['status' => $status]);
            $query->where('status_bayar', $status);
        }
        // Enable query logging
        DB::enableQueryLog();
        
        $transaksis = $query->orderBy('id_transaksi', 'desc')
                        ->paginate(10)
                        ->withQueryString();

        // Log the executed query
        Log::info('Executed Queries:', DB::getQueryLog());
        Log::info('Total Records:', ['count' => $transaksis->total()]);
        Log::info('Current Records:', ['data' => $transaksis->items()]);
        
        $produks = Produk::all();

        return view('transaksi.transaksi', [
            'transaksis' => $transaksis,
            'produks' => $produks,
            'title' => 'Transaksi'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | SQL Manual Query
        |--------------------------------------------------------------------------
        | POST /transaksi
        |
        | INSERT INTO transaksis
        |   (kode_transaksi, nama_customer, id_produk, tgl_jual, jumlah, total_harga, status_bayar)
        | VALUES
        |   (:kode_transaksi, :nama_customer, :id_produk, :tgl_jual, :jumlah, :total_harga, :status_bayar);
        |
        | (Sebelum insert: cek stok di tabel produks dan kurangi stok-nya.)
        */
        // Validasi input
        $validated = $request->validate([
            'nama_customer' => 'nullable|string|max:255',
            'id_produk' => 'required|exists:produks,id_produk',
            'tgl_jual' => 'required|date',
            'jumlah' => 'required|integer|min:1',
            'total_harga' => 'required|numeric|min:0',
            'status_bayar' => 'required|in:Belum Bayar,Sudah Bayar',
        ]);
    
        try {
            // Cek stok produk
            $produk = Produk::findOrFail($validated['id_produk']);
            if ($produk->stok < $validated['jumlah']) {
                return redirect()->route('transaksi.index')
                    ->with('error', 'Stok produk tidak mencukupi!');
            }

            // Generate kode transaksi
            $validated['kode_transaksi'] = Transaksi::generateKodeTransaksi();
            
            // Kurangi stok produk
            $produk->update([
                'stok' => $produk->stok - $validated['jumlah']
            ]);

            // Simpan transaksi
            Transaksi::create($validated);
    
            return redirect()->route('transaksi.index')
                ->with('success', 'Transaksi berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Log error
            Log::error('Error creating transaction: ' . $e->getMessage());
            
            return redirect()->route('transaksi.index')
                ->with('error', 'Gagal menambahkan transaksi! Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaksi $transaksi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $produks = Produk::all();

        return view('transaksi.edit', [
            'transaksi' => $transaksi,
            'produks' => $produks,
            'title' => 'Edit Transaksi'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        /*
        |--------------------------------------------------------------------------
        | SQL Manual Query
        |--------------------------------------------------------------------------
        | PUT /transaksi/{id}
        |
        | UPDATE transaksis
        | SET nama_customer = :nama_customer,
        |     id_produk     = :id_produk,
        |     jumlah        = :jumlah,
        |     tgl_jual      = :tgl_jual,
        |     total_harga   = :total_harga,
        |     status_bayar  = :status_bayar
        | WHERE id_transaksi = :id;
        |
        | (Sebelum update: hitung selisih jumlah dan sesuaikan stok di tabel produks.)
        */
        $transaksi = Transaksi::findOrFail($id);
        
        $request->validate([
            'nama_customer' => 'nullable|string|max:255',
            'id_produk' => 'required',
            'jumlah' => 'required|numeric',
            'tgl_jual' => 'required|date',
            'status_bayar' => 'required|in:Belum Bayar,Sudah Bayar'
        ]);
    
        try {
            $produk = Produk::findOrFail($request->id_produk);
            
            // Hitung perubahan jumlah
            $selisihJumlah = $request->jumlah - $transaksi->jumlah;
            
            // Cek stok jika ada penambahan jumlah
            if ($selisihJumlah > 0 && $produk->stok < $selisihJumlah) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok produk tidak mencukupi!'
                ], 400);
            }
            
            // Update stok produk
            $produk->update([
                'stok' => $produk->stok - $selisihJumlah
            ]);
            
            $total_harga = $produk->harga * $request->jumlah;
        
            $transaksi->update([
                'nama_customer' => $request->nama_customer,
                'id_produk' => $request->id_produk,
                'jumlah' => $request->jumlah,
                'tgl_jual' => $request->tgl_jual,
                'total_harga' => $total_harga,
                'status_bayar' => $request->status_bayar
            ]);
        
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diupdate!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        /*
        |--------------------------------------------------------------------------
        | SQL Manual Query
        |--------------------------------------------------------------------------
        | DELETE /transaksi/{id}
        |
        | DELETE FROM transaksis WHERE id_transaksi = :id;
        */
        try {
            $transaksi = Transaksi::findOrFail($id);
            $transaksi->delete();
            
            return redirect()->back()->with('success', 'Data transaksi berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data transaksi');
        }
    }
    
    public function export(Request $request) 
    {
        /*
        |--------------------------------------------------------------------------
        | SQL Manual Query
        |--------------------------------------------------------------------------
        | GET /transaksi/export?... (param filter sama seperti index)
        |
        | (Query dasar mirip index, di-build di App\Exports\TransaksiExport,
        |  lalu diexport ke Excel.)
        */
        return Excel::download(new TransaksiExport($request), 'transaksi_' . date('Y-m-d') . '.xlsx');
    }

    public function downloadPDF(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | SQL Manual Query
        |--------------------------------------------------------------------------
        | GET /transaksi/download/pdf?tanggal_awal=2025-01-01&tanggal_akhir=2025-01-31&status=Sudah+Bayar
        |
        | SELECT t.*, p.*
        | FROM transaksis t
        | LEFT JOIN produks p ON t.id_produk = p.id_produk
        | WHERE (:tanggal_awal  IS NULL OR DATE(t.created_at) >= :tanggal_awal)
        |   AND (:tanggal_akhir IS NULL OR DATE(t.created_at) <= :tanggal_akhir)
        |   AND (:jenis IS NULL OR t.jenis = :jenis)
        |   AND (:status IS NULL OR t.status_bayar = :status);
        */
        $query = Transaksi::with(['produk']);
    
        // Apply filters
        if ($request->filled('tanggal_awal')) {
            $query->whereDate('created_at', '>=', $request->tanggal_awal);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('created_at', '<=', $request->tanggal_akhir);
        }
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }
        // Tambah ini
        if ($request->filled('status')) {
            $query->where('status_bayar', $request->status);
        }
    
        $transaksis = $query->get();
        $pdf = PDF::loadView('transaksi.export.pdf', compact('transaksis'));
        return $pdf->download('transaksi.pdf');
    }

    public function downloadExcel(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | SQL Manual Query
        |--------------------------------------------------------------------------
        | GET /transaksi/download/excel?... (param filter sama seperti export/pdf)
        |
        | (Query dasar mirip export/pdf, di-handle di App\Exports\TransaksiExport.)
        */
        return Excel::download(new TransaksiExport($request), 'transaksi_' . date('Y-m-d') . '.xlsx');
    }

    public function downloadInvoice($id)
    {
        /*
        |--------------------------------------------------------------------------
        | SQL Manual Query
        |--------------------------------------------------------------------------
        | GET /transaksi/invoice/{id}
        |
        | SELECT t.*, p.*
        | FROM transaksis t
        | LEFT JOIN produks p ON t.id_produk = p.id_produk
        | WHERE t.id_transaksi = :id;
        */
        $transaksi = Transaksi::with('produk')->findOrFail($id);
        
        $pdf = PDF::loadView('transaksi.invoice', [
            'transaksi' => $transaksi
        ]);

        return $pdf->download('invoice-'.$transaksi->kode_transaksi.'.pdf');
    }
}