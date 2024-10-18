<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use App\Models\Surat;
use App\Models\JenisSurat;
use Illuminate\Http\Request;

class SuratController extends Controller
{
    public function index()
    {
        $surats = Surat::with('prodi', 'jenisSurat')->paginate(10);
        return view('surat.index', compact('surats'));
    }

    public function create()
    {
        $prodi = Prodi::all();
        $jenisSurat = JenisSurat::all();
        return view('surat.create', compact('prodi', 'jenisSurat'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'jenis_surat' => 'required',
            'prodi_id' => 'required',
            'nomor_surat' => 'required|unique:surat,nomor_surat',
            'perihal' => 'required',
            'isi' => 'required',
        ]);

        // Cari nomor terakhir berdasarkan program studi dan jenis surat
        $lastSurat = Surat::where('prodi_id', $request->prodi_id)
                          ->where('jenis_surat', $request->jenis_surat)
                          ->orderBy('nomor_per_prodi', 'desc')
                          ->first();
        $nextNomor = $lastSurat ? $lastSurat->nomor_per_prodi + 1 : 1;
        if (Surat::where('nomor_surat', $request->nomor_surat)->exists()) {
            return redirect()->back()->with('error', 'Nomor surat sudah ada. Silakan gunakan nomor surat yang berbeda.');
        }
    
        // Buat surat baru
        try {
            Surat::create([
                'nomor_per_prodi' => $nextNomor,
                'jenis_surat' => $request->jenis_surat,
                'prodi_id' => $request->prodi_id,
                'nomor_surat' => $request->nomor_surat,
                'perihal' => $request->perihal,
                'isi' => $request->isi,
            ]);

            return redirect()->route('surat.index')->with('success', 'Surat berhasil ditambahkan dengan nomor ' . $nextNomor);
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('surat.create')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(Surat $surat)
    {
        $prodi = Prodi::all();
        $jenisSurat = JenisSurat::all();
        return view('surat.edit', compact('surat', 'prodi', 'jenisSurat'));
    }

    public function update(Request $request, Surat $surat)
    {
        $request->validate([
            'nomor_per_prodi' => 'required|integer',
            'jenis_surat' => 'required',
            'prodi_id' => 'required',
            'nomor_surat' => 'required|unique:surat,nomor_surat,' . $surat->id,
            'perihal' => 'required',
            'isi' => 'required',
        ]);

        // Update surat dengan data dari request
        try {
            $surat->update([
                'nomor_per_prodi' => $request->nomor_per_prodi,
                'jenis_surat' => $request->jenis_surat,
                'prodi_id' => $request->prodi_id,
                'nomor_surat' => $request->nomor_surat,
                'perihal' => $request->perihal,
                'isi' => $request->isi,
            ]);

            return redirect()->route('surat.index')->with('success', 'Surat berhasil diupdate.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui surat: ' . $e->getMessage());
        }
    }

    public function destroy(Surat $surat)
    {
        $surat->delete();
        return redirect()->route('surat.index')->with('success', 'Surat berhasil dihapus.');
    }

    public function generateNomorPerProdi(Request $request)
    {
        $prodiId = $request->input('prodi_id');
        $jenisSuratId = $request->input('jenis_surat');
    
        // Mendapatkan nomor berikutnya
        $nextNomor = $this->getNextNomorPerProdi($prodiId, $jenisSuratId);
    
        return response()->json(['nextNomorPerProdi' => $nextNomor]);
    }
    
    private function getNextNomorPerProdi($prodiId, $jenisSuratId)
    {
        // Logika untuk mendapatkan nomor surat per prodi
        $lastSurat = Surat::where('prodi_id', $prodiId)
                          ->where('jenis_surat', $jenisSuratId)
                          ->orderBy('nomor_per_prodi', 'desc')
                          ->first();
        return $lastSurat ? $lastSurat->nomor_per_prodi + 1 : 1;
    }
    
}
