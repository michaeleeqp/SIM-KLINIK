@extends('layout.app')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Data Poliklinik — Asuhan Medis</h3>
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
                    <a href="#">Poliklinik</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Asuhan Medis</a>
                </li>
            </ul>
        </div>

        <!-- Filters -->
        <div class="card mb-3">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Filter Poliklinik</label>
                        <div class="btn-group w-100" role="group">
                            <a href="{{ route('ashuhans.index') }}" class="btn btn-outline-primary @if(!$poli) active @endif">
                                Semua
                            </a>
                            <a href="{{ route('ashuhans.index', ['poli' => 'ugd']) }}" class="btn btn-outline-primary @if($poli === 'ugd') active @endif">
                                UGD
                            </a>
                            <a href="{{ route('ashuhans.index', ['poli' => 'umum']) }}" class="btn btn-outline-primary @if($poli === 'umum') active @endif">
                                Umum
                            </a>
                            <a href="{{ route('ashuhans.index', ['poli' => 'rawat_inap']) }}" class="btn btn-outline-primary @if($poli === 'rawat_inap') active @endif">
                                Rawat Inap
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <form method="GET" class="d-flex gap-2 align-items-center">
                            <input type="date" name="date" class="form-control" value="{{ $date ?? '' }}">
                            
                            <button class="btn btn-primary">Filter</button>
                            <a href="{{ route('ashuhans.index') }}" class="btn btn-outline-secondary">Reset</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-striped">
                    <thead>
                        <tr>
                            <th>No RM</th>
                            <th>Nama Pasien</th>
                            <th>Poliklinik</th>
                            <th>Tanggal</th>
                            <th>Keluhan Utama</th>
                            <th>Diagnosa</th>
                            <th>Status</th>
                            <th>Pulang</th>
                            <th class="w-1">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ashuhans as $asuhan)
                            <tr>
                                <td><strong>{{ $asuhan->patient->no_rm ?? '-' }}</strong></td>
                                <td>{{ $asuhan->patient->nama_pasien ?? '-' }}</td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ ucfirst(str_replace('_', ' ', $asuhan->poli_tujuan)) }}
                                    </span>
                                </td>
                                <td>{{ $asuhan->tanggal_asuhan->format('d-m-Y') }}</td>
                                <td>{{ Str::limit($asuhan->keluhan_utama, 40) }}</td>
                                <td>{{ Str::limit($asuhan->diagnosa_medis, 40) }}</td>
                                <td>
                                    @if($asuhan->status === 'final')
                                        <span class="badge bg-success">Final</span>
                                    @else
                                        <span class="badge bg-warning">Draft</span>
                                    @endif
                                </td>
                                <td>
                                    @if($asuhan->discharge_date)
                                        <span class="badge bg-danger">✓ {{ $asuhan->discharge_date->format('d-m-Y') }}</span>
                                    @elseif($asuhan->admission_date)
                                        <span class="badge bg-info">Masuk: {{ $asuhan->admission_date->format('d-m-Y') }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-list flex-nowrap">
                                        <a href="{{ route('ashuhans.show', $asuhan) }}" class="btn btn-icon btn-ghost-info" title="Lihat">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg>
                                        </a>
                                        <a href="{{ route('ashuhans.edit', $asuhan) }}" class="btn btn-icon btn-ghost-warning" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                        </a>
                                        <form method="POST" action="{{ route('ashuhans.destroy', $asuhan) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-icon btn-ghost-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus?')">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v1" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <p>Belum ada data asuhan medis</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($ashuhans->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $ashuhans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection