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
                                <a class="nav-link active" data-bs-toggle="tab" href="#tab_ugd">
                                    List Pendaftaran UGD
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#tab_asuhan">
                                    Asuhan Medis
                                </a>
                            </li>

                        </ul>
                    </div>



                    {{-- =============== TAB CONTENT ================= --}}
                    <div class="card-body tab-content">

                        {{-- ========== TAB 1 : LIST UGD ========== --}}
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
                                                    <a href="{{ route('kunjungan.edit', $item->id) }}" class="btn btn-primary btn-sm">
                                                        <i class="fa fa-edit"></i>
                                                    </a>

                                                    <form action="{{ route('kunjungan.destroy', $item->id) }}" method="POST"
                                                          onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                        @csrf @method('DELETE')
                                                        <button class="btn btn-danger btn-sm">
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



                        {{-- ========== TAB 2 : ASUHAN MEDIS ========== --}}
                        <div class="tab-pane fade" id="tab_asuhan">
                            <form method="POST" action="#">
                                @csrf

                                <div class="row mt-3">

                                    {{-- DATA PASIEN --}}
                                    <div class="col-md-6 col-lg-4">
                                        <div class="mb-3">
                                            <label>No RM</label>
                                            <input type="text" class="form-control" name="no_rm" readonly>
                                        </div>

                                        <div class="mb-3">
                                            <label>Nama Pasien</label>
                                            <input type="text" class="form-control" name="nama_pasien" readonly>
                                        </div>

                                        <div class="mb-3">
                                            <label>Tanggal Lahir</label>
                                            <div class="input-group">
                                                <input type="date" class="form-control" id="tanggal_lahir" onchange="hitungUmur()" readonly>
                                                <span class="input-group-text" id="umur_display">Umur: -</span>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label>Jenis Kelamin</label>
                                            <input type="text" class="form-control" name="jenis_kelamin" readonly>
                                        </div>

                                        <div class="mb-3">
                                            <label>Golongan Darah</label>
                                            <input type="text" class="form-control" name="golongan_darah" readonly>
                                        </div>
                                    </div>


                                    {{-- ASUHAN MEDIS --}}
                                    <div class="col-md-12 col-lg-8">
                                        <div class="card border">
                                            <div class="card-header"><b>Asuhan Medis</b></div>
                                            <div class="card-body">

                                                
                                                    <div class="col-md-12 mb-3">
                                                        <label>Keluhan Utama</label>
                                                        <input type="text" class="form-control" name="keluhan_utama">
                                                    </div>

                                                    <div class="col-md-12 mb-3">
                                                        <label>Riwayat Penyakit</label>
                                                        <input type="text" class="form-control" name="riwayat_penyakit">
                                                    </div>

                                                    <div class="col-md-12 mb-3">
                                                        <label>Riwayat Alergi</label>
                                                        <input type="text" class="form-control" name="riwayat_alergi">
                                                    </div>

                                                    <div class="col-md-12 mb-3">
                                                        <label>Diagnosa Medis</label>
                                                        <input type="text" class="form-control" name="diagnosa_medis">
                                                    </div>

                                                    <div class="col-md-12 mb-3">
                                                        <label>Tindakan/Terapi</label>
                                                        <input type="text" class="form-control" name="tindakan_terapi">
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <label>Catatan Perawatan</label>
                                                        <textarea class="form-control" rows="3" name="catatan_perawatan"></textarea>
                                                    </div>
                                                

                                                <hr class="my-4">

                                                {{-- TANDA VITAL --}}
                                                <h5 class="fw-bold mb-2">Tanda-Tanda Vital</h5>
                                                <div class="row g-3">
                                                    <div class="col-md-3">
                                                        <label>Tekanan Darah</label>
                                                        <input type="text" class="form-control" name="tekanan_darah">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label>Nadi</label>
                                                        <input type="number" class="form-control" name="nadi">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label>Suhu (°C)</label>
                                                        <input type="number" step="0.1" class="form-control" name="suhu">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label>Respirasi</label>
                                                        <input type="number" class="form-control" name="respirasi">
                                                    </div>
                                                </div>

                                                

                                            </div>
                                        </div>
                                    </div>

                                </div>
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
    $("#basic-datatables").DataTable();

    function hitungUmur(){
        const tgl = document.getElementById("tanggal_lahir").value;
        if(!tgl) return;
        let umur = new Date().getFullYear() - new Date(tgl).getFullYear();
        document.getElementById("umur_display").innerHTML = "Umur: "+umur+" Tahun";
    }
</script>
@endpush
