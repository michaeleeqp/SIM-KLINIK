@php
    \Carbon\Carbon::setLocale('id');
@endphp

@extends('layout.app')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            {{-- Header Title --}}
            <h3 class="fw-bold mb-3">UGD</h3>
            
            {{-- Breadcrumbs (Path Navigation) --}}
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
                                            {{-- Menghitung umur pasien menggunakan Carbon --}}
                                            @php $umur = \Carbon\Carbon::parse($item->patient->tanggal_lahir)->age; @endphp
                                            <tr>
                                                <td>{{ $item->patient->no_rm }}</td>
                                                <td>
                                                    <b>{{ $item->patient->nama_pasien }}</b><br>
                                                    Umur: {{ $umur }} Tahun
                                                </td>
                                                <td>{{ $item->poli_tujuan }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->tanggal_kunjungan)->translatedFormat('d F Y') }}</td>
                                                <td>{{ $item->jenis_bayar }}</td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="" class="btn btn-primary btn-sm" title="Edit Data">
                                                            <i class="fa fa-edit"></i>
                                                        </a>

                                                        <form action="" method="POST"
                                                            onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                            
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
                            <form method="POST" action="#">
                                @csrf

                                {{-- SECTION 1: DATA PASIEN (Menggunakan card border untuk konsistensi) --}}
                                <div class="card border">
                                    <div class="card-body">
                                        <h5 class="fw-bold mb-3 text-primary">Data Pasien Kunjungan</h5>
                                        <div class="row">
                                            
                                            {{-- Kolom 1: KIRI (No RM, Nama Pasien) --}}
                                            <div class="col-12 col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">No RM</label>
                                                    <input type="text" class="form-control" name="no_rm" readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Nama Pasien</label>
                                                    <input type="text" class="form-control" name="nama_pasien" readonly>
                                                </div>
                                            </div>

                                            {{-- Kolom 2: TENGAH (Tanggal Lahir + Umur, Jenis Kelamin) --}}
                                            <div class="col-12 col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Tanggal Lahir</label>
                                                    <div class="input-group">
                                                        <input type="date" class="form-control" id="tanggal_lahir" onchange="hitungUmur()" readonly>
                                                        {{-- Display Umur (Menggunakan warna yang lebih netral/terang) --}}
                                                        <span class="input-group-text fw-bold text-dark" style="background-color: #e9ecef;" id="umur_display">Umur: -</span>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Jenis Kelamin</label>
                                                    <input type="text" class="form-control" name="jenis_kelamin" readonly>
                                                </div>
                                            </div>

                                            {{-- Kolom 3: KANAN (Golongan Darah, Catatan Kunjungan) --}}
                                            <div class="col-12 col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Golongan Darah</label>
                                                    <input type="text" class="form-control" name="golongan_darah" readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Catatan Kunjungan</label>
                                                    <textarea class="form-control" rows="1" name="catatan_kunjungan" readonly></textarea>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>


                                {{-- SECTION 2: ASUHAN MEDIS FORM (Menggunakan margin konsisten: mt-4) --}}
                                <div class="mt-4">
                                    <div class="card border">
                                        <div class="card-header"><b>Asuhan Medis</b></div>
                                        <div class="card-body">

                                            {{-- Anamnesa / Diagnosis --}}
                                            <div class="row">
                                                {{-- KONSISTENSI: Mengubah class="group" menjadi class="form-label" --}}
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">Keluhan Utama</label>
                                                    <input type="text" class="form-control" name="keluhan_utama">
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">Riwayat Penyakit</label>
                                                    <input type="text" class="form-control" name="riwayat_penyakit">
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">Riwayat Alergi</label>
                                                    <input type="text" class="form-control" name="riwayat_alergi">
                                                </div>
                                                <div class= "col-md-12 mb-3">
                                                    <label class="form-label">Diagnosa Medis</label>
                                                    <input type="text" class="form-control" name="diagnosa_medis">
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">Tindakan/Terapi</label>
                                                    <input type="text" class="form-control" name="tindakan_terapi">
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <label class="form-label">Catatan Perawatan</label>
                                                    <textarea class="form-control" rows="1" name="catatan_perawatan"></textarea>
                                                </div>
                                            </div>
                                            
                                            <hr class="my-4">

                                            {{-- TANDA VITAL (Menggunakan g-3 untuk konsistensi gap antar kolom) --}}
                                            <h5 class="fw-bold mb-3">Tanda-Tanda Vital</h5>
                                            <div class="row g-3">
                                                <div class="col-12 col-sm-6 col-md-3">
                                                    <label class="form-label">Tekanan Darah</label>
                                                    <input type="text" class="form-control" name="tekanan_darah" placeholder="contoh: 120/80 mmHg">
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-3">
                                                    <label class="form-label">Nadi (x/menit)</label>
                                                    <input type="number" class="form-control" name="nadi">
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-3">
                                                    <label class="form-label">Suhu (°C)</label>
                                                    <input type="number" step="0.1" class="form-control" name="suhu">
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-3">
                                                    <label class="form-label">Respirasi (x/menit)</label>
                                                    <input type="number" class="form-control" name="respirasi">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                {{-- FORM ACTIONS --}}
                                <div class="card-action d-flex gap-2">
                                    <button type="submit" class="btn btn-success">Submit</button>
                                    <button type="reset" class="btn btn-danger">Cancel</button>
                                </div>
                            </form>
                        </div>

                    </div> {{-- END TAB CONTENT --}}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script>
    // Initialize DataTables
    // Pastikan library DataTables dimuat di layout.app
    $(document).ready(function() {
        $("#basic-datatables").DataTable();
    });

    /**
     * Menghitung umur pasien berdasarkan tanggal lahir yang dipilih.
     */
    function hitungUmur(){
        const tglInput = document.getElementById("tanggal_lahir");
        const tgl = tglInput.value;

        if(!tgl) return;

        const birthDate = new Date(tgl);
        const today = new Date();
        
        let umur = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();

        // Mengurangi umur jika bulan sekarang kurang dari bulan lahir atau jika bulan sama
        // tetapi hari sekarang kurang dari hari lahir.
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            umur--;
        }

        document.getElementById("umur_display").innerHTML = "Umur: "+umur+" Tahun";
    }

    // Catatan: Anda perlu mengisi nilai input di tab "Asuhan Medis" (seperti No RM, Nama Pasien, dll.)
    // menggunakan data dari pendaftaran yang dipilih. Logika untuk mengisi data ini biasanya
    // menggunakan AJAX/JavaScript saat tombol aksi di List UGD diklik, tetapi itu
    // berada di luar cakupan Blade template ini.
</script>
@endpush