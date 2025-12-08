@extends('layout.app')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Laporan Resep Obat</h3>
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
                    <a href="#">Resep Obat</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="card-title">Filter & Laporan Resep Obat - {{ $monthLabel }}</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('laporan.resep') }}" class="mb-4">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">Bulan</label>
                                    <input type="month" name="month" class="form-control" value="{{ $month }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-control">
                                        <option value="">-- Semua Status --</option>
                                        <option value="belum" {{ $status === 'belum' ? 'selected' : '' }}>Belum Diambil</option>
                                        <option value="diambil" {{ $status === 'diambil' ? 'selected' : '' }}>Sudah Diambil</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fa fa-search"></i> Filter
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">&nbsp;</label>
                                    <a href="{{ route('laporan.resep.export', ['month' => $month, 'status' => $status]) }}" 
                                       class="btn btn-success w-100">
                                        <i class="fa fa-download"></i> Export Excel
                                    </a>
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
                                        <th>Poli</th>
                                        <th>Tanggal</th>
                                        <th>Detail Obat</th>
                                        <th>Total Harga</th>
                                        <th>Status</th>
                                        <th>Tgl Diambil</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reseps as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->patient->no_rm ?? '-' }}</td>
                                            <td>{{ $item->patient->nama_pasien ?? '-' }}</td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ ucfirst(str_replace('_', ' ', $item->poli_tujuan)) }}
                                                </span>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($item->tanggal_asuhan)->format('d-m-Y') }}</td>
                                            <td>
                                                @if(isset($item->prescriptionItems) && $item->prescriptionItems->isNotEmpty())
                                                    <ul class="mb-0">
                                                        @foreach($item->prescriptionItems as $it)
                                                            <li>{{ $it->name }} x{{ $it->qty }} @if(isset($it->price) && $it->price) - Rp {{ number_format($it->price,0,',','.') }} @endif</li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <small class="text-muted">{{ Str::limit(str_replace(["\r", "\n"], " ", $item->resep), 50, '...') }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $total = null;
                                                    if (!empty($item->resep_total)) {
                                                        $total = $item->resep_total;
                                                    } elseif (isset($item->prescriptionItems) && $item->prescriptionItems->isNotEmpty()) {
                                                        $sum = 0;
                                                        foreach ($item->prescriptionItems as $it) {
                                                            $price = isset($it->price) ? floatval($it->price) : 0;
                                                            $qty = isset($it->qty) ? intval($it->qty) : 0;
                                                            $sum += ($price * $qty);
                                                        }
                                                        $total = $sum;
                                                    }
                                                @endphp
                                                @if($total !== null)
                                                    Rp {{ number_format($total,0,',','.') }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->resep_collected_at)
                                                    <span class="badge bg-success">Sudah Diambil</span>
                                                @else
                                                    <span class="badge bg-warning">Belum Diambil</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->resep_collected_at)
                                                    {{ \Carbon\Carbon::parse($item->resep_collected_at)->format('d-m-Y H:i') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">Tidak ada data resep</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="text-muted">Total: <strong>{{ count($reseps) }}</strong> resep</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted text-end">
                                        Sudah Diambil: <strong>{{ $reseps->whereNotNull('resep_collected_at')->count() }}</strong> | 
                                        Belum Diambil: <strong>{{ $reseps->whereNull('resep_collected_at')->count() }}</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
