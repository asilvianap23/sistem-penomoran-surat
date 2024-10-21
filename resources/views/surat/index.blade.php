@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Surat</h1>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('surat.create') }}" class="btn btn-primary">Tambah Surat</a>

        <!-- Dropdown Daftar Entri -->
        <div>
            <form method="GET" action="{{ route('surat.index') }}" class="d-flex">
                <label class="mr-2 mt-2" for="limit">Tampilkan:</label>
                <select name="limit" class="form-control" id="limit" onchange="this.form.submit()">
                    <option value="10" {{ request()->get('limit') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request()->get('limit') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request()->get('limit') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request()->get('limit') == 100 ? 'selected' : '' }}>100</option>
                </select>
            </form>
        </div>
    </div>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('surat.index') }}" class="mb-3 d-flex flex-wrap align-items-end">
        <div class="d-flex align-items-end mr-2">
            <input type="text" name="tahun" class="form-control mr-2" placeholder="Tahun" value="{{ request()->get('tahun') }}" style="width: 100px;">
            <input type="text" name="nomor_per_prodi" class="form-control mr-2" placeholder="ID" value="{{ request()->get('nomor_per_prodi') }}" style="width: 80px;">
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
            <div id="prodi_container" style="display: {{ (request()->get('jenis_surat') == 1) ? 'block' : 'none' }};">
                <select name="prodi" class="form-control mr-2">
                    <option value="">Pilih Program Studi</option>
                    @foreach ($prodi as $item)
                        <option value="{{ $item->id }}" {{ request()->get('prodi') == $item->id ? 'selected' : '' }}>
                            {{ $item->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <input type="text" name="perihal" class="form-control mr-2" placeholder="Perihal" value="{{ request()->get('perihal') }}" style="width: 320px;">
            <button type="submit" class="btn btn-success">Filter</button>
            <a href="{{ route('surat.index') }}" class="btn btn-secondary ml-2">Reset</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>No</th>
                    <th>Tahun</th>
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
                        <td>{{ $surat->created_at->format('Y') }}</td>
                        <td>{{ $surat->nomor_per_prodi }}</td>
                        <td>{{ $surat->jenisSurat->nama }}</td>
                        <td class="program_studi" style="display: {{ ($surat->jenis_surat == 1 && request()->get('jenis_surat') == 1) ? 'table-cell' : 'none' }};">
                            {{ $surat->prodi ? $surat->prodi->nama : '-' }}
                        </td>
                        <td>{{ $surat->nomor_surat }}</td>
                        <td>{{ $surat->perihal }}</td>
                        <td>{{ $surat->isi }}</td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('surat.show', $surat->id) }}" class="btn btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('surat.edit', $surat->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('surat.destroy', $surat->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus surat ini?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
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

                if (selectedValue == '1') { 
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
