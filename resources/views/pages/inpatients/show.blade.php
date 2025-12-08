
@extends('layout.app')

@section('content')
<div class="container">
    <div class="page-inner">
        <h3 class="fw-bold mb-3">Detail Rawat Inap</h3>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row">
            <div class="col-md-5">
                <div class="card mb-3">
                    <div class="card-header"><h4>Informasi Pasien & Rujukan</h4></div>
                    <div class="card-body">
                        <p><strong>No RM:</strong> {{ $inpatient->kunjungan->patient->no_rm }}</p>
                        <p><strong>Nama:</strong> {{ $inpatient->kunjungan->patient->nama_pasien }}</p>
                        <p><strong>Tanggal Masuk:</strong> {{ optional($inpatient->admission_date)->format('d-m-Y H:i') ?? '-' }}</p>
                        <p><strong>Ward / Bed:</strong> {{ $inpatient->ward }} / {{ $inpatient->bed_number ?? '-' }}</p>
                        <p><strong>Alasan:</strong> {{ $inpatient->reason ?? '-' }}</p>
                        <p><strong>Rujukan Oleh:</strong> {{ optional($inpatient->referrer)->name ?? '-' }}</p>
                        <p><strong>Tanggal Pulang:</strong> {{ optional($inpatient->discharge_date)->format('d-m-Y H:i') ?? '-' }}</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h5>Pulangkan Pasien</h5></div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('inpatients.discharge', $inpatient->id) }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Tanggal Pulang</label>
                                <input type="datetime-local" name="discharge_date" class="form-control" value="{{ old('discharge_date') }}">
                            </div>
                            <button class="btn btn-danger">Pulangkan (Discharge)</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card mb-3">
                    <div class="card-header"><h5>Catatan Harian Rawat Inap</h5></div>
                    <div class="card-body">
                        @if($inpatient->records->isNotEmpty())
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Tindakan</th>
                                        <th>Pemberian Obat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($inpatient->records as $r)
                                        <tr>
                                            <td>{{ optional($r->record_date)->format('d-m-Y') }}</td>
                                            <td>{!! nl2br(e($r->tindakan)) !!}</td>
                                            <td>{!! nl2br(e($r->pemberian_obat)) !!}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>Belum ada catatan perawatan.</p>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h5>Tambah Catatan Harian</h5></div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('inpatients.records.store', $inpatient->id) }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Tanggal</label>
                                <input type="date" name="record_date" class="form-control" value="{{ old('record_date', now()->toDateString()) }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tindakan</label>
                                <textarea name="tindakan" rows="3" class="form-control">{{ old('tindakan') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Pemberian Obat (per hari)</label>
                                <textarea name="pemberian_obat" rows="3" class="form-control">{{ old('pemberian_obat') }}</textarea>
                            </div>
                            <button class="btn btn-primary">Simpan Catatan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
