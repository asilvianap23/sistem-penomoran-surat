@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 text-center">Detail Surat</h1>
    <div class="card">
        <div class="card-header text-white bg-primary">
            <h5 class="mb-0">{{ $surat->jenisSurat->nama }}</h5>
        </div>
        <div class="card-body">
            <table class="table">
                <tbody>
                    <tr>
                        <td><strong>ID:</strong></td>
                        <td class="text-muted">{{ $surat->nomor_per_prodi }}</td>
                    </tr>
                    <tr>
                        <td><strong>Nomor Surat:</strong></td>
                        <td class="text-muted">{{ $surat->nomor_surat }}</td>
                    </tr>
                    <tr>
                        <td><strong>Perihal:</strong></td>
                        <td class="text-muted">{{ $surat->perihal }}</td>
                    </tr>
                    <tr>
                        <td><strong>Isi Surat:</strong></td>
                        <td class="text-muted">
                            <div class="p-2 border rounded bg-light">
                                {{ $surat->isi }}
                            </div>
                        </td>
                    </tr>
                    @if ($surat->prodi)
                        <tr>
                            <td><strong>Program Studi:</strong></td>
                            <td class="text-muted">{{ $surat->prodi->nama }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td><strong>Tanggal Dibuat:</strong></td>
                        <td class="text-muted">{{ $surat->created_at->format('d-m-Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Lampiran:</strong></td>
                        <td class="text-muted">
                            @if ($surat->lampiran)
                                <a href="{{ Storage::url($surat->lampiran) }}" target="_blank">Lihat Lampiran</a>
                            @else
                                <span>-</span>
                            @endif
                            <div>{{ $surat->lampiran }}</div> <!-- Tampilkan path untuk debugging -->
                        </td>                        
                    </tr>
                </tbody>
            </table>
            <div class="text-center">
                <a href="{{ route('surat.index') }}" class="btn btn-primary">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
