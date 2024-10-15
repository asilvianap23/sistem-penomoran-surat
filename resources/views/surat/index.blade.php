@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Surat</h1>

    <a href="{{ route('surat.create') }}" class="btn btn-primary mb-3">Tambah Surat</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nomor</th>
                <th>Jenis Surat</th>
                <th>Program Studi</th>
                <th>Nomor Surat</th>
                <th>Perihal</th>
                <th>Isi Surat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($surats as $surat)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $surat->jenis_surat }}</td>
                    <td>
                        {{ $surat->prodi }}
                    </td>
                    <td>{{ $surat->nomor_surat }}</td>
                    <td>{{ $surat->perihal }}</td>
                    <td>{{ $surat->isi }}</td>
                    <td>
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
@endsection
