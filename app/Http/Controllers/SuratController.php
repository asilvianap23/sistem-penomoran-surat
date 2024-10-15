<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use App\Models\Surat;
use Illuminate\Http\Request;

class SuratController extends Controller
{
    public function index()
    {
        $surats = Surat::with('prodi')->get();
        return view('surat.index', compact('surats'));
    }

    public function create()
    {
        $prodi = Prodi::all();
        return view('surat.create', compact('prodi'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nomor_per_prodi' => 'required',
            'jenis_surat' => 'required',
            'prodi_id' => 'required',
            'nomor_surat' => 'required|unique:surat,nomor_surat',
            'perihal' => 'required',
            'isi' => 'required',
        ]);

        // Cek apakah ID sudah ada di database untuk prodi yang berbeda
        $existingSurat = Surat::where('nomor_per_prodi', $request->nomor_per_prodi)
                              ->where('prodi_id', '<>', $request->prodi_id)
                              ->first();

        // Cari nomor terakhir berdasarkan program studi
        $lastSurat = Surat::where('prodi_id', $request->prodi_id)->orderBy('nomor_per_prodi', 'desc')->first();
        $nextNomor = $lastSurat ? $lastSurat->nomor_per_prodi + 1 : 1;

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

            return redirect()->route('surat.create')->with('success', 'Surat berhasil ditambahkan dengan nomor ' . $nextNomor);
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('surat.create')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(Surat $surat)
    {
        $prodi = Prodi::all();
        return view('surat.edit', compact('surat', 'prodi'));
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

    public function generateNomorPerProdi($prodiId)
    {
        $lastSurat = Surat::where('prodi_id', $prodiId)->orderBy('nomor_per_prodi', 'desc')->first();
        $nextNomorPerProdi = $lastSurat ? $lastSurat->nomor_per_prodi + 1 : 1;

        return response()->json(['nextNomorPerProdi' => $nextNomorPerProdi]);
    }
}
