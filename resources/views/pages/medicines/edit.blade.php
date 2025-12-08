@extends('layout.app')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Edit Obat</h3>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('medicines.update', $medicine) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Nama Obat</label>
                        <input type="text" name="name" class="form-control" value="{{ $medicine->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <input type="text" name="category" class="form-control" value="{{ $medicine->category }}" placeholder="tablet, kapsul, syrup">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Satuan</label>
                        <input type="text" name="unit" class="form-control" value="{{ $medicine->unit }}" placeholder="strip, botol, tablet">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <input type="number" name="price" class="form-control" step="0.01" value="{{ $medicine->price }}" required>
                    </div>

                    <button class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
