@php
    \Carbon\Carbon::setLocale('id');
@endphp

@extends('layout.app')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">UGD</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="/dashboard"><i class="icon-home"></i></a>
                </li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Poliklinik</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">UGD</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    {{-- ================== TAB MENU ================== --}}
                    <div class="card-header pb-0">
                        <ul class="nav nav-tabs card-header-tabs d-flex gap-3">
                            <li class="nav-item">
                                <a class="nav-link active fw-bold mb-3" data-bs-toggle="tab" href="#tab_ugd">
                                    List Pendaftaran UGD
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-bold mb-3" data-bs-toggle="tab" href="#tab_asuhan">
                                    Asuhan Medis
                                </a>
                            </li>
                        </ul>
                    </div>

                    {{-- =============== TAB CONTENT ================= --}}
                    <div class="card-body tab-content">

                        {{-- ========== TAB 1 : LIST UGD (Table) ========== --}}
                        <div class="tab-pane fade show active" id="tab_ugd">
                            <div class="table-responsive mt-3">
                                <table id="basic-datatables" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>No RM</th>
                                            <th>Nama Pasien</th>
                                            <th>Poli Tujuan</th>
                                            <th>Tanggal Kunjungan</th>
                                            <th>Jenis Bayar</th>
                                            <th width="120">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data as $item)
                                            @php
                                                $umur = \Carbon\Carbon::parse($item->patient->tanggal_lahir)->age;
                                            @endphp
                                            <tr>
                                                <td>{{ $item->patient->no_rm }}</td>
                                                <td>
                                                    <b>{{ $item->patient->nama_pasien }}</b><br>
                                                    <small>Umur: {{ $umur }} Tahun</small>
                                                </td>
                                                <td>{{ $item->poli_tujuan }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->tanggal_kunjungan)->translatedFormat('d F Y') }}</td>
                                                <td>{{ $item->jenis_bayar }}</td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        {{-- Tombol Edit / Periksa --}}
                                                        <button class="btn btn-sm btn-primary btn-ugd-edit"
                                                            data-id="{{ $item->id }}"
                                                            title="Periksa Pasien">
                                                            <i class="fa fa-edit"></i>
                                                        </button>

                                                        {{-- Tombol Hapus --}}
                                                        <form action="{{ route('kunjungan.destroy', $item->id) }}" method="POST"
                                                            onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-danger btn-sm" title="Hapus Data">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- ========== TAB 2 : ASUHAN MEDIS (Data Pasien & Form) ========== --}}
                        <div class="tab-pane fade" id="tab_asuhan">
                            
                            {{-- SECTION 1: DATA PASIEN (Read Only) --}}
                            <div class="card border mb-4">
                                <div class="card-body">
                                    <h5 class="fw-bold mb-3 text-primary">Data Pasien Kunjungan</h5>
                                    <div class="row">
                                        {{-- Kolom 1: No RM, Nama --}}
                                        <div class="col-12 col-lg-4">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">No RM</label>
                                                <input type="text" class="form-control" id="asuh_no_rm" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Nama Pasien</label>
                                                <input type="text" class="form-control" id="asuh_nama" readonly>
                                            </div>
                                        </div>

                                        {{-- Kolom 2: Tgl Lahir, JK --}}
                                        <div class="col-12 col-lg-4">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Tanggal Lahir</label>
                                                <div class="input-group">
                                                    <input type="date" class="form-control" id="asuh_lahir" readonly>
                                                    <span class="input-group-text fw-bold text-dark" style="background-color: #e9ecef;" id="umur_display">
                                                        Umur: -
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Jenis Kelamin</label>
                                                <input type="text" class="form-control" id="asuh_jk" readonly>
                                            </div>
                                        </div>

                                        {{-- Kolom 3: Gol Darah, Catatan --}}
                                        <div class="col-12 col-lg-4">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Golongan Darah</label>
                                                <input type="text" class="form-control" id="asuh_gd" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Catatan Kunjungan</label>
                                                <textarea class="form-control" rows="1" id="asuh_catatan" readonly></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- SECTION 2: FORM INPUT ASUHAN MEDIS --}}
                            <div class="card border">
                                <div class="card-header">
                                    <b>Form Pemeriksaan Dokter</b>
                                </div>
                                <div class="card-body">
                                    {{-- Pastikan route ini benar di web.php --}}
                                    <form action="{{ route('ugd.store') }}" method="POST">
                                        @csrf
                                        
                                        {{-- INPUT HIDDEN PENTING: ID KUNJUNGAN --}}
                                        <input type="hidden" name="kunjungan_id" id="input_kunjungan_id" required>

                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label">Keluhan Utama</label>
                                                <input type="text" class="form-control" name="keluhan_utama" required>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label">Riwayat Penyakit</label>
                                                <input type="text" class="form-control" name="riwayat_penyakit">
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label">Riwayat Alergi</label>
                                                <input type="text" class="form-control" name="riwayat_alergi">
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label">Diagnosa Medis</label>
                                                <input type="text" class="form-control" name="diagnosa_medis" required>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label">Tindakan/Terapi</label>
                                                <input type="text" class="form-control" name="tindakan_terapi" required>
                                            </div>
                                            <div class="col-12 mb-3">
                                                <label class="form-label">Catatan Perawatan</label>
                                                <textarea class="form-control" rows="2" name="catatan_perawatan"></textarea>
                                            </div>
                                        </div>

                                        <hr class="my-4">
                                        <h5 class="fw-bold mb-3">Tanda-Tanda Vital</h5>

                                        <div class="row g-3">
                                            <div class="col-12 col-sm-6 col-md-3">
                                                <label class="form-label d-block">Tekanan Darah (mmHg)</label>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <input type="text" class="form-control text-center" name="sistole" placeholder="Sys" maxlength="3" style="width: 45%">
                                                    <span class="fw-bold">/</span>
                                                    <input type="text" class="form-control text-center" name="diastole" placeholder="Dia" maxlength="3" style="width: 45%">
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-3">
                                                <label class="form-label">Nadi (X/menit)</label>
                                                <input type="number" class="form-control" name="nadi" maxlength="3">
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-3">
                                                <label class="form-label">Suhu (°C)</label>
                                                <input type="number" step="0.1" class="form-control" name="suhu">
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-3">
                                                <label class="form-label">Respirasi (X/menit)</label>
                                                <input type="number" class="form-control" name="respirasi">
                                            </div>
                                        </div>

                                        <div class="card-action d-flex gap-2 mt-4">
                                            <button type="submit" class="btn btn-success">Simpan Data Medis</button>
                                            <button type="reset" class="btn btn-danger">Reset</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div> 
                        {{-- END TAB 2 --}}

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $("#basic-datatables").DataTable();

        // 1. Fungsi Hitung Umur
        function hitungUmur() {
            let tgl = $("#asuh_lahir").val();
            if (!tgl) return;

            let d = new Date(tgl);
            let now = new Date();
            let umur = now.getFullYear() - d.getFullYear();

            if (now.getMonth() < d.getMonth() || (now.getMonth() == d.getMonth() && now.getDate() < d.getDate())) {
                umur--;
            }
            $("#umur_display").text("Umur: " + umur + " Tahun");
        }

        // 2. Event Listener Tombol Edit
        $(".btn-ugd-edit").click(function (e) {
            e.preventDefault();
            
            // Ambil ID Kunjungan dari tombol
            let id = $(this).data("id");

            // AJAX Request
            $.get("{{ url('/ugd/get') }}/" + id, function (res) {
                if (res.success) {
                    // Isi Data Pasien (Read Only)
                    $("#asuh_no_rm").val(res.data.rm);
                    $("#asuh_nama").val(res.data.nama);
                    $("#asuh_lahir").val(res.data.lahir);
                    $("#asuh_jk").val(res.data.jk);
                    $("#asuh_gd").val(res.data.goldar);
                    $("#asuh_catatan").val(res.data.catatan);

                    // Hitung ulang umur di display
                    hitungUmur();

                    // [PENTING] Isi Input Hidden ID Kunjungan agar form bisa disubmit
                    $("#input_kunjungan_id").val(id);

                    // Pindah ke Tab Asuhan Medis
                    let tabEl = document.querySelector('a[href="#tab_asuhan"]');
                    let tabInstance = new bootstrap.Tab(tabEl);
                    tabInstance.show();
                    
                    // Opsional: Scroll ke bagian atas form
                    document.getElementById('tab_asuhan').scrollIntoView({ behavior: 'smooth' });
                } else {
                    alert('Gagal mengambil data pasien.');
                }
            }).fail(function() {
                alert('Terjadi kesalahan koneksi.');
            });
        });
    });
</script>
@endpush