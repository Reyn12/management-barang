<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use App\Models\Supplier;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProdukExport;


class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Produk::with('supplier');
    
        // Filter berdasarkan pencarian
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_produk', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%")
                  ->orWhere('spesifikasi', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function($q) use ($search) {
                      $q->where('nama_supplier', 'like', "%{$search}%");
                  });
            });
        }
    
        // Filter berdasarkan kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori', $request->kategori);
        }
    
        // Filter berdasarkan supplier
        if ($request->has('supplier') && $request->supplier != '') {
            $query->where('id_supplier', $request->supplier);
        }
    
        // Filter berdasarkan range harga
        if ($request->has('min_harga') && $request->min_harga != '') {
            $query->where('harga', '>=', $request->min_harga);
        }
        if ($request->has('max_harga') && $request->max_harga != '') {
            $query->where('harga', '<=', $request->max_harga);
        }
    
        $produk = $query->latest()->paginate(10); // Menggunakan paginate untuk pagination
        $suppliers = Supplier::all();
    
        return view('produk.produk', compact('suppliers', 'produk'));
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
        $request->validate([
            'nama_produk' => 'required',
            'kategori' => 'required',
            'id_supplier' => 'required',
            'harga' => 'required|numeric|min:0',
            'spesifikasi' => 'nullable',
            'foto_produk' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'stok' => 'required|numeric|min:0'
        ]);

        // Handle foto upload
        $foto_path = null;
        if ($request->hasFile('foto_produk')) {
            $foto = $request->file('foto_produk');
            $foto_path = $foto->store('produk', 'public');
        }

        // Simpan data produk
        Produk::create([
            'nama_produk' => $request->nama_produk,
            'kategori' => $request->kategori,
            'id_supplier' => $request->id_supplier,
            'harga' => $request->harga,
            'spesifikasi' => $request->spesifikasi,
            'foto_produk' => $foto_path,
            'stok' => $request->stok
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Produk $produk)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id) // Ubah dari (Produk $produk) jadi ($id)
    {
        $produk = Produk::findOrFail($id);
        $suppliers = Supplier::all();
        return view('produk.edit', compact('produk', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $produk = Produk::findOrFail($id);
            
            Log::info('Update Request Data:', $request->all());
            Log::info('Current Produk Data:', $produk->toArray());
        
            $request->validate([
                'nama_produk' => 'required',
                'kategori' => 'required',
                'id_supplier' => 'required',
                'harga' => 'required|numeric|min:0',
                'stok' => 'required|numeric|min:0',
                'spesifikasi' => 'nullable',
                'foto_produk' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);
        
            $data = [
                'nama_produk' => $request->nama_produk,
                'kategori' => $request->kategori,
                'id_supplier' => $request->id_supplier,
                'harga' => $request->harga,
                'stok' => $request->stok,
                'spesifikasi' => $request->spesifikasi
            ];
    
            // Handle foto produk jika ada
            if ($request->hasFile('foto_produk')) {
                // Hapus foto lama jika ada
                if ($produk->foto_produk) {
                    Storage::disk('public')->delete($produk->foto_produk);
                }
    
                // Upload foto baru
                $foto = $request->file('foto_produk');
                $data['foto_produk'] = $foto->store('produk', 'public');
            }
        
            Log::info('Data to update:', $data);
            
            DB::enableQueryLog();
            $updated = $produk->update($data);
            Log::info('SQL Query:', DB::getQueryLog());
            Log::info('Update result:', ['success' => $updated]);
            Log::info('Updated Produk Data:', $produk->fresh()->toArray());
        
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produk berhasil diperbarui'
                ]);
            }
    
            return redirect()->route('produk.produk')->with('success', 'Produk berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Update error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui produk: ' . $e->getMessage()
                ], 422);
            }
            
            return back()->with('error', 'Gagal memperbarui produk: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $produk = Produk::findOrFail($id);
            
            // Hapus foto jika ada
            if($produk->foto_produk) {
                Storage::disk('public')->delete($produk->foto_produk);
            }
            
            $produk->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus produk: ' . $e->getMessage()
            ], 500);
        }
    }

    public function downloadPDF(Request $request)
    {
        // Debug full request
        Log::info('Full request: ' . json_encode($request->all()));

        $query = Produk::with('supplier');

        // Filter by kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter by supplier (hanya jika supplier dipilih)
        if ($request->filled('supplier')) {
            $query->where('id_supplier', $request->supplier);
        }

        // Debug query
        Log::info('Query: ' . $query->toSql());
        Log::info('Query bindings: ' . json_encode($query->getBindings()));

        $produks = $query->get();
        
        // Debug result
        Log::info('Result count: ' . $produks->count());

        $pdf = Pdf::loadView('produk.export.pdf', compact('produks'));
        return $pdf->download('produk.pdf');
    }

    public function downloadExcel(Request $request)
    {
        return Excel::download(new ProdukExport($request), 'produk.xlsx');
    }
}