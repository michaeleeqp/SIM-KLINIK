@php
    \Carbon\Carbon::setLocale('id');
@endphp

@extends('layout.app')

@section('content')
<div class="container">
    <div class="page-inner">

        <div class="page-header">
            <h3 class="fw-bold mb-3">Edit Kunjungan Pasien</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="/dashboard">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Pendaftaran</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Edit Kunjungan</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-8 mx-auto">

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Form Edit Kunjungan</h4>
                    </div>

                    <div class="card-body">

                        <form action="{{ route('kunjungan.update', $kunjungan->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            {{-- DATA PASIEN (READONLY) --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Pasien</label>
                                <input type="text" class="form-control" value="{{ $kunjungan->patient->nama_pasien }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">No Rekam Medis</label>
                                <input type="text" class="form-control" value="{{ $kunjungan->patient->no_rm }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Tanggal Lahir</label>
                                <input type="text" class="form-control" value="{{ $kunjungan->patient->tanggal_lahir }}" readonly>
                            </div>

                            <hr>

                            {{-- POLI TUJUAN --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">Poli Tujuan</label>
                                <select name="poli_tujuan" class="form-select" required>
                                    <option value="">-- Pilih Poli --</option>
                                    <option value="UGD" {{ $kunjungan->poli_tujuan == 'UGD' ? 'selected' : '' }}>UGD</option>
                                    <option value="umum" {{ $kunjungan->poli_tujuan == 'umum' ? 'selected' : '' }}>Poliklinik Umum</option>
                                    <option value="Rawat_inap" {{ $kunjungan->poli_tujuan == 'Rawat_inap`' ? 'selected' : '' }}>Rawat Inap</option>
                                </select>
                            </div>

                            {{-- TANGGAL KUNJUNGAN --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tanggal Kunjungan</label>
                                <input type="date" name="tanggal_kunjungan" class="form-control"
                                       value="{{ $kunjungan->tanggal_kunjungan }}" required>
                            </div>

                            {{-- JENIS PEMBAYARAN --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">Jenis Pembayaran</label>
                                <select name="jenis_bayar" id="jenis_bayar" class="form-select" required>
                                    <option value="">-- Pilih Pembayaran --</option>
                                    <option value="Umum" {{ $kunjungan->jenis_bayar == 'Umum' ? 'selected' : '' }}>Umum</option>
                                    <option value="BPJS PBI" {{ $kunjungan->jenis_bayar == 'BPJS PBI' ? 'selected' : '' }}>BPJS PBI</option>
                                    <option value="BPJS NON PBI" {{ $kunjungan->jenis_bayar == 'BPJS NON PBI' ? 'selected' : '' }}>BPJS NON PBI</option>
                                    <option value="JAMKESDA" {{ $kunjungan->jenis_bayar == 'JAMKESDA' ? 'selected' : '' }}>JAMKESDA</option>
                                </select>
                            </div>

                            {{-- Input No Asuransi --}}
                            <div class="mb-3" id="no_asuransi_group">
                                <label class="form-label fw-bold">No Asuransi</label>
                                <input type="text" id="no_asuransi" name="no_asuransi" class="form-control"
                                      value="{{ $kunjungan->no_asuransi }}">
                                <small class="text-muted">BPJS wajib 13 digit angka</small>
                            </div>

                            <div class="text-end">
                              <button type="submit" class="btn btn-success">Submit</button>  
                              <a href="{{ route('list.pendaftaran') }}" class="btn btn-danger">Cancel</a>                                
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
@push('scripts')
<script>
    function toggleNoAsuransi() {
        const jenis = document.getElementById('jenis_bayar').value;
        const group = document.getElementById('no_asuransi_group');
        const input = document.getElementById('no_asuransi');

        if (jenis === 'Umum') {
            group.style.display = 'none';
            input.removeAttribute('required');
            input.value = '';
        } else {
            group.style.display = 'block';
            input.setAttribute('required', 'required');
        }
    }

    // Ketika halaman dibuka, cek pilihan awal
    toggleNoAsuransi();

    // Ketika user mengubah opsi jenis bayar
    document.getElementById('jenis_bayar').addEventListener('change', toggleNoAsuransi);

    // Validasi input no BPJS (13 digit angka)
    document.getElementById('no_asuransi').addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, ''); // hanya angka
        if (this.value.length > 13) {
            this.value = this.value.slice(0, 13);
        }
    });
</script>
@endpush
