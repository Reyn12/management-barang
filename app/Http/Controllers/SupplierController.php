<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SupplierExport;
use Illuminate\Validation\ValidationException;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | SQL Manual Query
        |--------------------------------------------------------------------------
        | GET /supplier
        |
        | SELECT *
        | FROM suppliers
        | ORDER BY created_at DESC;
        */
        $suppliers = Supplier::latest()->get();
        return view('supplier.supplier', compact('suppliers'));
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
        try {
            /*
            |--------------------------------------------------------------------------
            | SQL Manual Query
            |--------------------------------------------------------------------------
            | POST /supplier
            |
            | INSERT INTO suppliers
            |   (nama_supplier, alamat, no_telp, email)
            | VALUES
            |   (:nama_supplier, :alamat, :no_telp, :email);
            */
            $validated = $request->validate([
                'nama_supplier' => 'required|unique:suppliers,nama_supplier',
                'alamat' => 'required',
                'no_telp' => 'required|unique:suppliers,no_telp',
                'email' => 'required|email|unique:suppliers,email'
            ], [
                'nama_supplier.unique' => 'Nama supplier ini sudah ada',
                'nama_supplier.required' => 'Nama supplier harus diisi',
                'alamat.required' => 'Alamat harus diisi',
                'no_telp.required' => 'Nomor telepon harus diisi',
                'no_telp.unique' => 'Nomor telepon ini sudah dipakai',
                'email.required' => 'Email harus diisi',
                'email.email' => 'Format email tidak valid',
                'email.unique' => 'Email ini sudah dipakai'
            ]);
        
            $supplier = Supplier::create($validated);
        
            if(!$supplier) {
                throw new \Exception('Gagal menyimpan data supplier');
            }

            return response()->json([
                'success' => true,
                'message' => 'Supplier berhasil ditambahkan'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating supplier: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan supplier: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            /*
            |--------------------------------------------------------------------------
            | SQL Manual Query
            |--------------------------------------------------------------------------
            | PUT /supplier/{id}
            |
            | UPDATE suppliers
            | SET nama_supplier = :nama_supplier,
            |     alamat        = :alamat,
            |     no_telp       = :no_telp,
            |     email         = :email
            | WHERE id_supplier = :id;
            */
            Log::info('Update supplier request:', [
                'id' => $id,
                'request' => $request->all()
            ]);
            
            $supplier = Supplier::findOrFail($id);
            
            $updated = $supplier->update([
                'nama_supplier' => $request->nama,
                'alamat' => $request->alamat, 
                'no_telp' => $request->telepon,
                'email' => $request->email
            ]);

            if (!$updated) {
                throw new \Exception('Gagal update data supplier');
            }

            return response()->json([
                'success' => true,
                'message' => 'Data supplier berhasil diupdate'
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating supplier:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)  // Ubah parameter dari (Supplier $supplier)
    {
        /*
        |--------------------------------------------------------------------------
        | SQL Manual Query
        |--------------------------------------------------------------------------
        | DELETE /supplier/{id}
        |
        | DELETE FROM suppliers WHERE id_supplier = :id;
        */
        $supplier = Supplier::find($id);
        if($supplier) {
            $supplier->delete();
            return response()->json([
                'success' => true,
                'message' => 'Supplier berhasil dihapus'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Supplier tidak ditemukan'
        ], 404);
    }

    public function downloadPDF()
    {
        /*
        |--------------------------------------------------------------------------
        | SQL Manual Query
        |--------------------------------------------------------------------------
        | GET /supplier/download/pdf
        |
        | SELECT *
        | FROM suppliers;
        */
        $suppliers = Supplier::all();
        $pdf = Pdf::loadView('supplier.export.pdf', compact('suppliers'));
        return $pdf->download('supplier.pdf');
    }

    public function downloadExcel()
    {
        /*
        |--------------------------------------------------------------------------
        | SQL Manual Query
        |--------------------------------------------------------------------------
        | GET /supplier/download/excel
        |
        | (Query dasar sama seperti PDF, di-handle di App\Exports\SupplierExport)
        */
        return Excel::download(new SupplierExport, 'supplier.xlsx');
    }

}
