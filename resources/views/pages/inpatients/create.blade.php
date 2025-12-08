@extends('layout.app')

@section('content')
<div class="container">
    <div class="page-inner">
        <h3>Rujuk Rawat Inap - {{ $kunjungan->patient->name }}</h3>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('inpatients.store', $kunjungan->id) }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Tanggal Masuk</label>
                <input type="datetime-local" name="admission_date" class="form-control" value="{{ old('admission_date') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Ward</label>
                <input type="text" name="ward" class="form-control" value="{{ old('ward') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Bed Number</label>
                <input type="text" name="bed_number" class="form-control" value="{{ old('bed_number') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Alasan / Catatan</label>
                <textarea name="reason" class="form-control" rows="4">{{ old('reason') }}</textarea>
            </div>
            <button class="btn btn-warning">Rujuk ke Rawat Inap</button>
        </form>
    </div>
</div>
@endsection

