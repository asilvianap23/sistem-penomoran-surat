<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use App\Models\Surat;
use Illuminate\Http\Request;

class SuratController extends Controller
{
    public function index()
    {
        $surats = Surat::with('prodi')->get(); // Memuat relasi prodi
        return view('surat.index', compact('surats'));
    }
    

    public function create()
    {
        // Menghitung jumlah surat yang ada dan menentukan ID berikutnya
        $lastSurat = Surat::orderBy('id', 'desc')->first();
        $nextId = $lastSurat ? $lastSurat->id + 1 : 1;
        
        $prodi = Prodi::all();

        return view('surat.create', compact('nextId', 'prodi'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nomor_per_prodi' => 'required',
            'jenis_surat' => 'required',
            'prodi' => 'required',
            'nomor_surat' => 'required|unique:surat,nomor_surat',
            'perihal' => 'required',
            'isi' => 'required',
        ]);
    
        // Cek apakah ID sudah ada di database untuk prodi yang berbeda
        $existingSurat = Surat::where('nomor_per_prodi', $request->nomor_per_prodi)
                              ->where('prodi', '<>', $request->prodi)
                              ->first();
    
        if ($existingSurat) {
            // ID sudah ada untuk prodi yang berbeda, biarkan proses tetap berjalan
        }
    
        // Cari nomor terakhir berdasarkan program studi
        $lastSurat = Surat::where('prodi', $request->prodi)->orderBy('nomor_per_prodi', 'desc')->first();
        $nextNomor = $lastSurat ? $lastSurat->nomor_per_prodi + 1 : 1;  // Menghitung nomor berikutnya berdasarkan prodi
    
        // Buat surat baru
        try {
            Surat::create([
                'nomor_per_prodi' => $nextNomor,  // Set nomor urut berdasarkan prodi
                'jenis_surat' => $request->jenis_surat,
                'prodi' => $request->prodi,
                'nomor_surat' => $request->nomor_surat,
                'perihal' => $request->perihal,
                'isi' => $request->isi,
            ]);
            // Log::info('Menyimpan surat dengan ID: ' . $request->nomor_per_prodi);

    
            return redirect()->route('surat.create')->with('success', 'Surat berhasil ditambahkan dengan nomor ' . $nextNomor);
        } catch (\Exception $e) {
            return redirect()->route('surat.create')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    


    public function edit(Surat $surat)
    {
        return view('surat.edit', compact('surat'));
    }

    public function update(Request $request, Surat $surat)
    {
        $request->validate([
            'nomor_per_prodi' => 'required|integer',
            'jenis_surat' => 'required',
            'prodi' => 'required',
            'nomor_surat' => 'required|unique:surat,nomor_surat,'.$surat->id,
            'perihal' => 'required',
            'isi' => 'required',
        ]);

        $surat->update($request->all());

        return redirect()->route('surat.index')->with('success', 'Surat berhasil diupdate.');
    }

    public function destroy(Surat $surat)
    {
        $surat->delete();
        return redirect()->route('surat.index')->with('success', 'Surat berhasil dihapus.');
    }

    public function generateNomorPerProdi($prodiId)
    {
        $lastSurat = Surat::where('prodi', $prodiId)->orderBy('nomor_per_prodi', 'desc')->first();
        $nextNomorPerProdi = $lastSurat ? $lastSurat->nomor_per_prodi + 1 : 1;
    
        return response()->json(['nextNomorPerProdi' => $nextNomorPerProdi]);
    }

}
