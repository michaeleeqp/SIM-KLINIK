@extends('layout.app')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Kelola Obat</h3>
        </div>

        <div class="card">
            <div class="card-body">
                <a href="{{ route('medicines.create') }}" class="btn btn-primary mb-3">Tambah Obat</a>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Satuan</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($medicines as $m)
                                <tr>
                                    <td>{{ $m->name }}</td>
                                    <td>{{ $m->category }}</td>
                                    <td>{{ $m->unit }}</td>
                                    <td>{{ number_format($m->price,0,',','.') }}</td>
                                    <td>
                                        <a href="{{ route('medicines.edit', $m) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('medicines.destroy', $m) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus obat ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
