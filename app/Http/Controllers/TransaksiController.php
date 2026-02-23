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
            ->orWhere('id_transaksi', 'like', "%{$search}%");
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
        // Validasi input
        $validated = $request->validate([
            'id_produk' => 'required|exists:produks,id_produk',
            'tgl_jual' => 'required|date',
            'jumlah' => 'required|integer|min:1',
            'total_harga' => 'required|numeric|min:0',
            'status_bayar' => 'required|in:Pending,Sukses,Gagal',
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
        $transaksi = Transaksi::findOrFail($id);
        
        $request->validate([
            'id_produk' => 'required',
            'jumlah' => 'required|numeric',
            'tgl_jual' => 'required|date',
            'status_bayar' => 'required'
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
        return Excel::download(new TransaksiExport($request), 'transaksi_' . date('Y-m-d') . '.xlsx');
    }

    public function downloadPDF(Request $request)
    {
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
        return Excel::download(new TransaksiExport($request), 'transaksi_' . date('Y-m-d') . '.xlsx');
    }

    public function downloadInvoice($id)
    {
        $transaksi = Transaksi::with('produk')->findOrFail($id);
        
        $pdf = PDF::loadView('transaksi.invoice', [
            'transaksi' => $transaksi
        ]);

        return $pdf->download('invoice-'.$transaksi->kode_transaksi.'.pdf');
    }
}