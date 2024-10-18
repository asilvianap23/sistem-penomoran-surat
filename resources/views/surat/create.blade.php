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
                <input type="text" name="nomor_per_prodi" class="form-control readonly" id="nomor_per_prodi" readonly>
                <div class="input-group-append">
                    <button type="button" class="btn btn-secondary" id="generateId">Generate</button>
                </div>
            </div>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        function getNomorSuratPerProdi(prodiId, jenisSuratId) {
            if (prodiId && jenisSuratId) {
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
                            // swal("Sukses!", "Nomor surat per prodi berhasil didapatkan.", "success");
                        } else {
                            $('#nomor_per_prodi').val(''); // Kosongkan jika tidak ada respons
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error:', error);
                        console.error('XHR:', xhr);
                        alert('Terjadi kesalahan saat mendapatkan nomor per prodi. Status: ' + status);
                    }
                });
            } else {
                $('#nomor_per_prodi').val(''); // Kosongkan jika prodi dan jenis surat tidak dipilih
            }
        }

        $('#prodi_id, #jenis_surat').change(function() {
            var prodiId = $('#prodi_id').val();
            var jenisSuratId = $('#jenis_surat').val();
            getNomorSuratPerProdi(prodiId, jenisSuratId);
        });

        $('#generateId').click(function() {
            var prodiId = $('#prodi_id').val();
            var jenisSuratId = $('#jenis_surat').val();
            if (!prodiId || !jenisSuratId) {
                alert('Pilih program studi dan jenis surat terlebih dahulu');
                return;
            }
            getNomorSuratPerProdi(prodiId, jenisSuratId);
        });
    });
</script>
@endsection
