@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Surat Baru</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('surat.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="prodi_id">Program Studi</label>
            <select name="prodi_id" id="prodi_id" class="form-control" required>
                <option value="">Pilih Program Studi</option>
                @foreach ($prodi as $item)
                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                @endforeach
            </select>            
        </div>

        <div class="form-group">
            <label for="nomor_per_prodi">ID Surat</label>
            <div class="input-group">
                <input type="text" name="nomor_per_prodi" class="form-control" id="nomor_per_prodi" readonly>
                <div class="input-group-append">
                    <button type="button" class="btn btn-secondary" id="generateId">Generate</button>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="jenis_surat">Jenis Surat</label>
            <input type="text" name="jenis_surat" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="nomor_surat">Nomor Surat</label>
            <input type="text" name="nomor_surat" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="perihal">Perihal</label>
            <input type="text" name="perihal" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="isi">Isi Surat</label>
            <textarea name="isi" class="form-control" rows="5" required></textarea>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('surat.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<script>
    // Fungsi untuk mendapatkan nomor surat per prodi
    function getNomorSuratPerProdi(prodiId) {
        if (prodiId) {
            fetch(`/surat/generate-nomor-perprodi/${prodiId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('nomor_per_prodi').value = data.nextNomorPerProdi;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        } else {
            document.getElementById('nomor_per_prodi').value = '';
        }
    }

    document.getElementById('prodi_id').addEventListener('change', function() {
        getNomorSuratPerProdi(this.value);
    });

    document.getElementById('generateId').addEventListener('click', function() {
        var prodiId = document.getElementById('prodi_id').value;
        if (prodiId) {
            getNomorSuratPerProdi(prodiId);
        } else {
            alert('Pilih program studi terlebih dahulu');
        }
    });
</script>
@endsection
