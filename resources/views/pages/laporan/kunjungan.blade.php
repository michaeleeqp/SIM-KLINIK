@extends('layout.app')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Laporan Kunjungan</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="#">
                        <i class="fas fa-layer-group"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Laporan</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Kunjungan</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="card-title">Filter & Laporan Kunjungan - {{ $monthLabel }}</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('laporan.kunjungan') }}" class="mb-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">Bulan</label>
                                    <input type="month" name="month" class="form-control" value="{{ $month }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Poliklinik</label>
                                    <select name="poli" class="form-control">
                                        <option value="">-- Semua Poli --</option>
                                        <option value="ugd" {{ $poli === 'ugd' ? 'selected' : '' }}>UGD</option>
                                        <option value="umum" {{ $poli === 'umum' ? 'selected' : '' }}>Klinik Umum</option>
                                        <option value="rawat_inap" {{ $poli === 'rawat_inap' ? 'selected' : '' }}>Rawat Inap</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fa fa-search"></i> Filter
                                        </button>
                                        <a href="{{ route('laporan.kunjungan.export', ['month' => $month, 'poli' => $poli]) }}" 
                                           class="btn btn-success">
                                            <i class="fa fa-download"></i> Export Excel
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No RM</th>
                                        <th>Nama Pasien</th>
                                        <th>Tanggal Kunjungan</th>
                                        <th>Poli Tujuan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($kunjungans as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->patient->no_rm ?? '-' }}</td>
                                            <td>{{ $item->patient->nama_pasien ?? '-' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->tanggal_kunjungan)->format('d-m-Y H:i') }}</td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ ucfirst(str_replace('_', ' ', $item->poli_tujuan)) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">Tidak ada data kunjungan</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            <p class="text-muted">Total: <strong>{{ count($kunjungans) }}</strong> kunjungan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
