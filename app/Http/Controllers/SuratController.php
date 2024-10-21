<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use App\Models\Surat;
use App\Models\JenisSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuratController extends Controller
{
    public function index(Request $request)
    {
        $query = Surat::query()->with('prodi', 'jenisSurat'); // Menyertakan relasi
        $prodi = Prodi::all();
        $jenisSurat = JenisSurat::all();

        if ($request->has('tahun') && $request->tahun != '') {
            $query->whereYear('created_at', $request->tahun);
        }
        // Filter berdasarkan nomor surat
        if ($request->filled('nomor_per_prodi')) {
            $query->where('nomor_per_prodi', 'like', '%' . $request->nomor_per_prodi . '%');
        }

        // Filter berdasarkan jenis surat
        if ($request->filled('jenis_surat')) {
            $query->where('jenis_surat', $request->jenis_surat);
        }

        // Filter berdasarkan prodi
        if ($request->filled('prodi')) {
            $query->where('prodi_id', $request->prodi);
        }

        // Filter berdasarkan perihal
        if ($request->filled('perihal')) {
            $query->where('perihal', 'like', '%' . $request->perihal . '%');
        }

        $limit = $request->get('limit', 10); // Default adalah 10 jika tidak dipilih
        $surats = $query->paginate($limit);

        return view('surat.index', compact('surats', 'jenisSurat', 'prodi'));
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
            'jenis_surat' => 'required|integer',
            'prodi_id' => $request->input('jenis_surat') == 1 ? 'required|integer' : 'nullable|integer',
            'nomor_surat' => 'required|unique:surat,nomor_surat|string',
            'perihal' => 'required|string',
            'isi' => 'required|string',
        ]);

        // Tentukan prodi_id
        $prodiId = $request->input('jenis_surat') == 1 ? $request->prodi_id : null;

        // Cari nomor terakhir berdasarkan program studi dan jenis surat
        $lastSurat = Surat::where('prodi_id', $prodiId)
                          ->where('jenis_surat', $request->jenis_surat)
                          ->orderBy('nomor_per_prodi', 'desc')
                          ->first();
        $nextNomor = $lastSurat ? $lastSurat->nomor_per_prodi + 1 : 1;

        // Buat surat baru
        try {
            Surat::create([
                'nomor_per_prodi' => $nextNomor,
                'jenis_surat' => $request->jenis_surat,
                'prodi_id' => $prodiId,
                'nomor_surat' => $request->nomor_surat,
                'perihal' => $request->perihal,
                'isi' => $request->isi,
            ]);

            return redirect()->route('surat.index')->with('success', 'Surat berhasil ditambahkan dengan nomor ' . $nextNomor);
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('surat.create')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    public function show($id)
    {
        $surat = Surat::findOrFail($id);
        return view('surat.show', compact('surat'));
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
            'prodi_id' => $request->input('jenis_surat') == 1 ? 'required|integer' : 'nullable|integer',
            'nomor_surat' => 'required|unique:surat,nomor_surat,' . $surat->id,
            'perihal' => 'required|string',
            'isi' => 'required|string',
            'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ]);
    
        // Tentukan prodi_id
        $prodiId = $request->input('jenis_surat') == 1 ? $request->prodi_id : null;
    
        // Update surat dengan data dari request
        try {
            // Variabel untuk menyimpan path lampiran
            $lampiranPath = $surat->lampiran; // Inisialisasi dengan lampiran lama
    
            // Cek jika ada file lampiran baru
            if ($request->hasFile('lampiran')) {
                // Dapatkan nama file asli dan tambah timestamp untuk menghindari bentrok
                $originalName = pathinfo($request->file('lampiran')->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $request->file('lampiran')->getClientOriginalExtension();
                $newLampiranName = $originalName . '_' . time() . '.' . $extension; // Menambahkan timestamp
    
                // Hapus lampiran lama jika ada
                if ($lampiranPath) {
                    Storage::disk('public')->delete($lampiranPath);
                }
    
                // Menyimpan file ke storage dengan nama baru
                $lampiranPath = $request->file('lampiran')->storeAs('lampiran', $newLampiranName, 'public');
            }
    
            // Update data surat
            $surat->update([
                'nomor_per_prodi' => $request->nomor_per_prodi,
                'jenis_surat' => $surat->jenis_surat, // Pastikan ini tetap
                'prodi_id' => $prodiId,
                'nomor_surat' => $request->nomor_surat,
                'perihal' => $request->perihal,
                'isi' => $request->isi,
                'lampiran' => $lampiranPath, // Update lampiran
            ]);
    
            // Mengembalikan respon JSON untuk AJAX
            return response()->json(['success' => 'Surat berhasil diupdate.']);
        } catch (\Illuminate\Database\QueryException $e) {
            // Mengembalikan respon JSON jika terjadi kesalahan
            return response()->json(['error' => 'Gagal memperbarui surat: ' . $e->getMessage()], 500);
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

        // Jika jenis surat tidak memerlukan prodi
        if (in_array($jenisSuratId, [/* ID jenis surat yang tidak butuh prodi */])) {
            $lastSurat = Surat::where('jenis_surat', $jenisSuratId)
                ->orderBy('nomor_per_prodi', 'desc')
                ->first();
        } else {
            $lastSurat = Surat::where('prodi_id', $prodiId)
                ->where('jenis_surat', $jenisSuratId)
                ->orderBy('nomor_per_prodi', 'desc')
                ->first();
        }

        // Ambil nomor berikutnya
        $nextNomor = $lastSurat ? $lastSurat->nomor_per_prodi + 1 : 1;

        return response()->json(['nextNomorPerProdi' => $nextNomor]);
    }

    public function checkNomor(Request $request)
    {
        $request->validate(['nomor_surat' => 'required|string']);

        $exists = Surat::where('nomor_surat', $request->nomor_surat)->exists();

        return response()->json(['exists' => $exists]);
    }

    public function filterProdi(Request $request)
    {
        $jenisSuratId = $request->input('jenis_surat');

        if ($jenisSuratId == 1) { // Misalnya, surat dengan ID 1 memerlukan prodi
            $prodi = Prodi::all(); // Tampilkan semua prodi
        } else {
            $prodi = []; // Kosongkan jika jenis surat tidak membutuhkan prodi
        }
        
        return response()->json(['prodi' => $prodi]);
    }
}
