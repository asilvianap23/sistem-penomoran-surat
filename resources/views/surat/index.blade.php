@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Surat</h1>

    <a href="{{ route('surat.create') }}" class="btn btn-primary mb-3">Tambah Surat</a>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('surat.index') }}" class="mb-3 d-flex flex-wrap align-items-end">
        <div class="mr-2" style="width: 80px;">
            <input type="text" name="nomor_per_prodi" class="form-control" placeholder="ID" value="{{ request()->get('nomor_per_prodi') }}">
        </div>
        <div class="mr-2">
            <select name="jenis_surat" class="form-control" id="jenis_surat">
                <option value="">Pilih Jenis Surat</option>
                @foreach ($jenisSurat as $item)
                    <option value="{{ $item->id }}" {{ request()->get('jenis_surat') == $item->id ? 'selected' : '' }}>
                        {{ $item->nama }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mr-2" id="prodi_container" style="display: {{ (request()->get('jenis_surat') == 1) ? 'block' : 'none' }};">
            <select name="prodi" class="form-control">
                <option value="">Pilih Program Studi</option>
                @foreach ($prodi as $item)
                    <option value="{{ $item->id }}" {{ request()->get('prodi') == $item->id ? 'selected' : '' }}>
                        {{ $item->nama }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mr-2" style="width: 320px;">
            <input type="text" name="perihal" class="form-control" placeholder="Perihal" value="{{ request()->get('perihal') }}">
        </div>
        <div>
            <button type="submit" class="btn btn-success">Filter</button>
            <a href="{{ route('surat.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>No</th>
                    <th>ID</th>
                    <th>Jenis Surat</th>
                    <th id="program_studi_header" style="display: {{ (request()->get('jenis_surat') == 1 && count($surats) > 0) ? 'table-cell' : 'none' }};">Program Studi</th>
                    <th>Nomor Surat</th>
                    <th>Perihal</th>
                    <th>Isi Surat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($surats as $surat)
                    <tr>
                        <td>{{ $loop->iteration + ($surats->currentPage() - 1) * $surats->perPage() }}</td>
                        <td>{{ $surat->nomor_per_prodi }}</td>
                        <td>{{ $surat->jenisSurat->nama }}</td>
                        <td class="program_studi" style="display: {{ ($surat->jenis_surat == 1 && request()->get('jenis_surat') == 1) ? 'table-cell' : 'none' }};">
                            {{ $surat->prodi ? $surat->prodi->nama : '-' }}
                        </td>
                        <td>{{ $surat->nomor_surat }}</td>
                        <td>{{ $surat->perihal }}</td>
                        <td>{{ $surat->isi }}</td>
                        <td class="text-center">
                            <a href="{{ route('surat.edit', $surat->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('surat.destroy', $surat->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus surat ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-3">
        {{ $surats->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
    </div>

    @section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const jenisSuratSelect = document.getElementById('jenis_surat');
            const prodiHeader = document.getElementById('program_studi_header');
            const prodiCells = document.querySelectorAll('.program_studi');
            const prodiContainer = document.getElementById('prodi_container');

            const updateProdiVisibility = () => {
                const selectedValue = jenisSuratSelect.value;

                // Ganti '1' dengan ID yang sesuai untuk jenis surat "Metopen"
                if (selectedValue == '1') { // ID untuk Metopen
                    prodiHeader.style.display = 'table-cell';
                    prodiCells.forEach(cell => {
                        if (cell.textContent.trim() !== '-') {
                            cell.style.display = 'table-cell';
                        }
                    });
                    prodiContainer.style.display = 'block'; // Menampilkan dropdown prodi
                } else {
                    prodiHeader.style.display = 'none';
                    prodiCells.forEach(cell => cell.style.display = 'none');
                    prodiContainer.style.display = 'none'; // Menyembunyikan dropdown prodi
                }
            };

            jenisSuratSelect.addEventListener('change', updateProdiVisibility);
            updateProdiVisibility(); // Inisialisasi status saat halaman dimuat
        });
    </script>
    @endsection
</div>
@endsection
