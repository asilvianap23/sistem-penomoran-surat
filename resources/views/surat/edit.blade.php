@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Surat</h1>

    <form action="{{ route('surat.update', $surat->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nomor_per_prodi">Nomor Per Prodi</label>
            <input type="number" name="nomor_per_prodi" class="form-control" value="{{ $surat->nomor_per_prodi }}" readonly>
        </div>
        

        <div class="form-group">
            <label for="jenis_surat">Jenis Surat</label>
            <input type="text" name="jenis_surat" class="form-control" value="{{ $surat->jenis_surat }}" required>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <div class="form-group">
            <label for="prodi_id">Program Studi</label>
            <select name="prodi_id" id="prodi_id" class="form-control" readonly>
                <option value="">Pilih Program Studi</option>
                @foreach ($prodi as $item)
                    <option value="{{ $item->id }}" {{ $item->id == $surat->prodi_id ? 'selected' : '' }}>
                        {{ $item->nama}} 
                    </option> 
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="nomor_surat">Nomor Surat</label>
            <input type="text" name="nomor_surat" class="form-control" value="{{ $surat->nomor_surat }}" required>
        </div>

        <div class="form-group">
            <label for="perihal">Perihal</label>
            <input type="text" name="perihal" class="form-control" value="{{ $surat->perihal }}" required>
        </div>

        <div class="form-group">
            <label for="isi">Isi Surat</label>
            <textarea name="isi" class="form-control" rows="5" required>{{ $surat->isi }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update Surat</button>
        <a href="{{ route('surat.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
