
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

        {{-- Notifikasi sukses / error --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <!-- Patient Info -->
            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Pasien</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">No RM</label>
                            <p class="form-control-static"><strong>{{ $asuhan->patient->no_rm }}</strong></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Pasien</label>
                            <p class="form-control-static">{{ $asuhan->patient->nama_pasien }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <p class="form-control-static">{{ ucfirst($asuhan->patient->jenis_kelamin) }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <p class="form-control-static">{{ $asuhan->patient->tanggal_lahir->format('d-m-Y') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-auto">
                <div class="btn-list flex-nowrap">
                    <a href="{{ route('ashuhans.edit', $asuhan) }}" class="btn btn-primary">Edit</a>
                    @if(strtolower($asuhan->poli_tujuan) === 'ugd' || strtolower($asuhan->poli_tujuan) === 'umum')
                        <form id="rujuk-form" action="{{ route('ashuhans.rujuk', $asuhan) }}" method="POST" style="display:inline-block;">
                            @csrf
                            <button type="button" class="btn btn-danger" onclick="if(confirm('Yakin rujuk pasien ini ke Rawat Inap?')){ document.getElementById('rujuk-form').submit(); }">Rujuk</button>
                        </form>
                    @endif
                    <a href="{{ route('ashuhans.index', ['poli' => $asuhan->poli_tujuan]) }}" class="btn btn-ghost-primary">Kembali</a>
                </div>
            </div>
            </div>

            <!-- Asuhan Details -->
            <div class="col-lg-8">
                <!-- Header Info -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Asuhan</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Poliklinik</label>
                                <p class="form-control-static">
                                    <span class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $asuhan->poli_tujuan)) }}</span>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Asuhan</label>
                                <p class="form-control-static">{{ $asuhan->tanggal_asuhan->format('d-m-Y') }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Oleh</label>
                                <p class="form-control-static">{{ $asuhan->user->name ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <p class="form-control-static">
                                    @if($asuhan->status === 'final')
                                        <span class="badge bg-success">Final</span>
                                    @else
                                        <span class="badge bg-warning">Draft</span>
                                    @endif
                                </p>
                            </div>
                            @if(strtolower($asuhan->poli_tujuan) === 'rawat_inap')
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal Masuk Rawat Inap</label>
                                    <p class="form-control-static">{{ $asuhan->admission_date ? $asuhan->admission_date->format('d-m-Y H:i') : '-' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal Pulang</label>
                                    <p class="form-control-static">
                                        @if($asuhan->discharge_date)
                                            <span class="badge bg-danger">{{ $asuhan->discharge_date->format('d-m-Y H:i') }}</span>
                                        @else
                                            <span class="text-muted">Belum Pulang</span>
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                @if(strtolower($asuhan->poli_tujuan) === 'rawat_inap')
                    <!-- Update Discharge Date -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Update Tanggal Pulang</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('ashuhans.update-discharge', $asuhan) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="mb-3">
                                    <label class="form-label">Tanggal & Waktu Pulang</label>
                                    <input type="datetime-local" name="discharge_date" class="form-control @error('discharge_date') is-invalid @enderror" value="{{ $asuhan->discharge_date ? $asuhan->discharge_date->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i') }}" required>
                                    @error('discharge_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-success">{{ $asuhan->discharge_date ? 'Update Tanggal Pulang' : 'Set Tanggal Pulang' }}</button>
                                @if($asuhan->discharge_date)
                                    <button type="button" class="btn btn-warning" onclick="if(confirm('Hapus tanggal pulang?')){ document.getElementById('clear-discharge-form').submit(); }">Hapus Tanggal Pulang</button>
                                @endif
                            </form>
                            @if($asuhan->discharge_date)
                                <form id="clear-discharge-form" action="{{ route('ashuhans.clear-discharge', $asuhan) }}" method="POST" style="display:none;">
                                    @csrf
                                    @method('PATCH')
                                </form>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Keluhan & Riwayat -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Keluhan & Riwayat</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Keluhan Utama</label>
                            <p class="form-control-static">{{ $asuhan->keluhan_utama }}</p>
                        </div>
                        @if($asuhan->riwayat_penyakit)
                            <div class="mb-3">
                                <label class="form-label">Riwayat Penyakit</label>
                                <p class="form-control-static">{{ $asuhan->riwayat_penyakit }}</p>
                            </div>
                        @endif
                        @if($asuhan->riwayat_alergi)
                            <div class="mb-3">
                                <label class="form-label">Riwayat Alergi</label>
                                <p class="form-control-static">{{ $asuhan->riwayat_alergi }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Diagnosa & Tindakan -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Diagnosa & Tindakan</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Diagnosa Medis</label>
                            <p class="form-control-static">{{ $asuhan->diagnosa_medis }}</p>
                        </div>
                        @if($asuhan->tindakan_terapi)
                            <div class="mb-3">
                                <label class="form-label">Tindakan / Terapi</label>
                                <p class="form-control-static">{{ $asuhan->tindakan_terapi }}</p>
                            </div>
                        @endif
                        @php
                            $hasItems = isset($asuhan->prescriptionItems) && $asuhan->prescriptionItems->isNotEmpty();
                        @endphp

                        @if($hasItems)
                            <div class="mb-3">
                                <label class="form-label">Resep / Obat</label>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Nama Obat</th>
                                                <th>Unit</th>
                                                <th>Harga</th>
                                                <th>Jumlah</th>
                                                <th>Catatan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($asuhan->prescriptionItems as $item)
                                                <tr>
                                                    <td>{{ $item->name }}</td>
                                                    <td>{{ $item->unit ?? '-' }}</td>
                                                    <td>{{ $item->price ? number_format($item->price, 0, ',', '.') : '-' }}</td>
                                                    <td>{{ $item->qty }}</td>
                                                    <td>{{ $item->note ?? '' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <p class="mt-2">
                                    @if($asuhan->resep_collected_at)
                                        <span class="badge bg-success">Sudah Diambil {{ $asuhan->resep_collected_at->format('d-m-Y H:i') }}</span>
                                    @else
                                        <span class="badge bg-warning">Belum Diambil (Ke Farmasi)</span>
                                    @endif
                                </p>
                            </div>
                        @elseif($asuhan->resep)
                            <div class="mb-3">
                                <label class="form-label">Resep / Obat</label>
                                @php
                                    $resepArr = null;
                                    try {
                                        $decoded = json_decode($asuhan->resep, true);
                                        if (is_array($decoded)) {
                                            $resepArr = $decoded;
                                        }
                                    } catch (\Throwable $e) {
                                        $resepArr = null;
                                    }
                                @endphp

                                @if(is_array($resepArr) && count($resepArr) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Nama Obat</th>
                                                    <th>Unit</th>
                                                    <th>Harga</th>
                                                    <th>Jumlah</th>
                                                    <th>Catatan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($resepArr as $item)
                                                    <tr>
                                                        <td>{{ $item['name'] ?? ($item['nama'] ?? '-') }}</td>
                                                        <td>{{ $item['unit'] ?? '-' }}</td>
                                                        <td>{{ isset($item['price']) ? number_format(floatval($item['price']), 0, ',', '.') : '-' }}</td>
                                                        <td>{{ $item['qty'] ?? ($item['jumlah'] ?? '-') }}</td>
                                                        <td>{{ $item['note'] ?? ($item['catatan'] ?? '') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="form-control-static">{!! nl2br(e($asuhan->resep)) !!}</p>
                                @endif

                                <p class="mt-2">
                                    @if($asuhan->resep_collected_at)
                                        <span class="badge bg-success">Sudah Diambil {{ $asuhan->resep_collected_at->format('d-m-Y H:i') }}</span>
                                    @else
                                        <span class="badge bg-warning">Belum Diambil (Ke Farmasi)</span>
                                    @endif
                                </p>
                            </div>
                        @endif
                        @if($asuhan->catatan_perawatan)
                            <div class="mb-3">
                                <label class="form-label">Catatan Perawatan</label>
                                <p class="form-control-static">{{ $asuhan->catatan_perawatan }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Vital Signs -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Tanda-Tanda Vital</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tekanan Darah</label>
                                <p class="form-control-static">{{ $asuhan->tekanan_darah ?? '-' }} mmHg</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nadi</label>
                                <p class="form-control-static">{{ $asuhan->nadi ?? '-' }} x/menit</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Suhu</label>
                                <p class="form-control-static">{{ $asuhan->suhu ?? '-' }} °C</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Respirasi</label>
                                <p class="form-control-static">{{ $asuhan->respirasi ?? '-' }} x/menit</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection