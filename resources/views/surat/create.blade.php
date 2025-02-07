@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Surat Baru</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('surat.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="jenis_surat">Jenis Surat</label>
            <select name="jenis_surat" id="jenis_surat" class="form-control" required>
                <option value="">Pilih Jenis Surat</option>
                @foreach ($jenisSurat as $item)
                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group" id="prodi_group" style="display: none;">
            <label for="prodi_id">Program Studi</label>
            <select name="prodi_id" id="prodi_id" class="form-control">
                <option value="">Pilih Program Studi</option>
                @foreach ($prodi as $item)
                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                @endforeach
            </select>            
        </div>

        <div class="form-group">
            <label for="nomor_per_prodi">ID Surat</label>
            <div class="input-group">
                <input type="text" name="nomor_per_prodi" class="form-control readonly" id="nomor_per_prodi" readonly>
                <div class="input-group-append">
                    <button type="button" class="btn btn-secondary" id="generateId">Generate</button>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="nomor_surat">Nomor Surat</label>
            <input type="text" name="nomor_surat" class="form-control" required id="nomor_surat">
            <div id="nomor_surat_error" class="text-danger" style="display: none;">Nomor surat sudah ada.</div>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Fungsi untuk mendapatkan nomor surat per program studi
        function getNomorSuratPerProdi(prodiId, jenisSuratId) {
            $.ajax({
                url: '{{ route('surat.generateNomorPerProdi') }}',
                type: 'POST',
                data: {
                    prodi_id: prodiId,
                    jenis_surat: jenisSuratId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.nextNomorPerProdi) {
                        $('#nomor_per_prodi').val(response.nextNomorPerProdi);
                    } else {
                        $('#nomor_per_prodi').val(''); // Kosongkan jika tidak ada nomor
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', error);
                    alert('Terjadi kesalahan saat mendapatkan nomor per prodi. Status: ' + status);
                }
            });
        }

        // Menangani perubahan pada dropdown Jenis Surat
        $('#jenis_surat').change(function() {
            var jenisSuratId = $(this).val();
            // Tampilkan atau sembunyikan dropdown Prodi
            if (jenisSuratId == 1) { // Ganti ID 1 dengan ID yang sesuai
                $('#prodi_group').show(); // Tampilkan dropdown Prodi
            } else {
                $('#prodi_group').hide(); // Sembunyikan dropdown Prodi
                getNomorSuratPerProdi(null, jenisSuratId); // Generate nomor tanpa prodi
            }
        });

        // Menangani perubahan pada dropdown Program Studi
        $('#prodi_id').change(function() {
            var prodiId = $(this).val();
            var jenisSuratId = $('#jenis_surat').val();
            if (jenisSuratId) {
                getNomorSuratPerProdi(prodiId, jenisSuratId);
            }
        });

        // Menangani tombol Generate ID
        $('#generateId').click(function() {
            var prodiId = $('#prodi_id').val();
            var jenisSuratId = $('#jenis_surat').val();

            if (!jenisSuratId) {
                alert('Pilih jenis surat terlebih dahulu.');
                return;
            }

            if (jenisSuratId != 1) { // Ganti ID 1 dengan ID yang sesuai
                getNomorSuratPerProdi(null, jenisSuratId); // Panggil tanpa prodi
            } else if (!prodiId) {
                alert('Pilih program studi terlebih dahulu.');
            } else {
                getNomorSuratPerProdi(prodiId, jenisSuratId); // Panggil dengan prodi
            }
        });

        // Validasi nomor surat yang dimasukkan manual
        $('#nomor_surat').on('input', function() {
            var nomorSurat = $(this).val();
            if (nomorSurat) {
                $.ajax({
                    url: '{{ route('surat.checkNomor') }}',
                    type: 'POST',
                    data: {
                        nomor_surat: nomorSurat,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#nomor_surat_error').toggle(response.exists); // Tampilkan/hilangkan pesan kesalahan
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error:', error);
                    }
                });
            } else {
                $('#nomor_surat_error').hide(); // Sembunyikan pesan jika input kosong
            }
        });

        // Pastikan dropdown Prodi tersembunyi saat halaman pertama kali dimuat
        $('#prodi_group').hide();
    });
</script>

@endsection
