@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Surat</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="editSuratForm" action="{{ route('surat.update', $surat->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
    
        <div class="form-group">
            <label for="nomor_per_prodi">ID</label>
            <input type="text" name="nomor_per_prodi" class="form-control readonly" value="{{ $surat->nomor_per_prodi }}" readonly>
        </div>
    
        <div class="form-group">
            <label for="jenis_surat">Jenis Surat</label>
            <div class="form-group">
                <input type="text" class="form-control" value="{{ $jenisSurat->where('id', $surat->jenis_surat)->first()->nama }}" readonly>
            </div>
        </div>
        
        <div class="form-group" id="prodi_group" style="display: none;">
            <label for="prodi_id">Program Studi</label>
            <select name="prodi_id" id="prodi_id" class="form-control" required disabled>
                <option value="">Pilih Program Studi</option>
                @foreach ($prodi as $item)
                    <option value="{{ $item->id }}" {{ $item->id == $surat->prodi_id ? 'selected' : '' }}>
                        {{ $item->nama }}
                    </option>
                @endforeach
            </select>
        </div>
    
        <div class="form-group">
            <label for="nomor_surat">Nomor Surat</label>
            <input type="text" name="nomor_surat" class="form-control" value="{{ $surat->nomor_surat }}" required>
            <div id="nomor_surat_error" class="text-danger" style="display: none;">Nomor surat sudah ada.</div>
        </div>
    
        <div class="form-group">
            <label for="perihal">Perihal</label>
            <input type="text" name="perihal" class="form-control" value="{{ $surat->perihal }}" required>
        </div>
    
        <div class="form-group">
            <label for="isi">Isi Surat</label>
            <textarea name="isi" class="form-control" rows="5" required>{{ $surat->isi }}</textarea>
        </div>
    
        <div class="form-group">
            <label for="lampiran">Lampiran (opsional)</label>
            <input type="file" name="lampiran" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png">
            @if ($surat->lampiran)
                <div class="mt-2">
                    <strong>Lampiran Saat Ini:</strong>
                    <a href="{{ Storage::url($surat->lampiran) }}" target="_blank">{{ basename($surat->lampiran) }}</a>
                </div>
            @endif
        </div>
    
        <button type="submit" class="btn btn-primary">Update Surat</button>
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
            if (jenisSuratId == 1) { // Misal, 1 adalah ID untuk Bebas Pustaka
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

        // Cek jenis surat saat halaman dimuat
        var jenisSuratId = $('#jenis_surat').val();
        if (jenisSuratId == 1) { // Ganti 1 dengan ID untuk Bebas Pustaka
            $('#prodi_group').show(); // Tampilkan dropdown Prodi jika jenis surat adalah Bebas Pustaka
        } else {
            $('#prodi_group').hide(); // Sembunyikan dropdown Prodi jika jenis surat bukan Bebas Pustaka
        }
    });

    $('#editSuratForm').on('submit', function(event) {
        event.preventDefault(); // Mencegah pengalihan form

        $.ajax({
            url: $(this).attr('action'), // Ambil URL dari action form
            type: 'POST',
            data: new FormData(this), // Mengirim data form
            contentType: false,
            processData: false,
            success: function(response) {
                // Tampilkan pesan sukses atau lakukan sesuatu
                alert('Surat berhasil diupdate.');
                // Merefresh halaman setelah notifikasi
                location.reload(); // Menambahkan ini untuk merefresh halaman
            },
            error: function(xhr) {
                // Tampilkan pesan kesalahan
                var errors = xhr.responseJSON.errors;
                var errorMessage = '';
                if (errors) {
                    for (var key in errors) {
                        errorMessage += errors[key][0] + '\n';
                    }
                }
                alert('Gagal memperbarui surat:\n' + errorMessage);
            }
        });
    });
</script>

@endsection
