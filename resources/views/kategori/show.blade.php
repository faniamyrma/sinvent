@extends('layouts.adm-main')

@section('content')
    <div class="container">
        <h1>Detail Kategori</h1>
        <div class="form-group">
            <label for="kategori">Kategori</label>
            <input type="text" id="kategori" class="form-control" value="{{ $rsetKategori->kategori }}" readonly>
        </div>
        <div class="form-group">
            <label for="jenis">Jenis</label>
            <input type="text" id="jenis" class="form-control" value="{{ $rsetKategori->jenis }}" readonly>
        </div>
        <a href="{{ route('kategori.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
@endsection
