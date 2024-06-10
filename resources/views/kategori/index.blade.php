@extends('layouts.adm-main')

@section('content')
    <div class="container">
        <h1>Daftar Kategori</h1>
        <a href="{{ route('kategori.create') }}" class="btn btn-primary">Tambah Kategori</a>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('gagal'))
            <div class="alert alert-danger">{{ session('gagal') }}</div>
        @endif
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kategori</th>
                    <th>Jenis</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rsetKategori as $kategori)
                    <tr>
                        <td>{{ $kategori->id }}</td>
                        <td>{{ $kategori->kategori }}</td>
                        <td>{{ $kategori->jenis }}</td>
                        <td>{{ $kategori->ketKategori }}</td>
                        <td>
                            <a href="{{ route('kategori.show', $kategori->id) }}" class="btn btn-info">Lihat</a>
                            <a href="{{ route('kategori.edit', $kategori->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('kategori.destroy', $kategori->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $rsetKategori->links() }}
    </div>
@endsection
