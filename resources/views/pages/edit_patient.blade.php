@extends('layout.app')

@section('content')
<div class="container"> <!-- PERBAIKAN: Spasi dihapus -->
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Pendaftaran</h3>
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
                    <a href="#">Pendaftaran</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Pasien baru</a>
                </li>
            </ul>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Pendaftaran Pasien Baru</div>
                    </div>
                    
                    <form action="{{ route('patient.update', $patient->id) }}" method="POST">
                        @csrf 
                        @method('PUT')
                        
                        <div class="card-body">
                            <div class="row">
                                <!-- KOLOM KIRI -->
                                <div class="col-md-6 col-lg-4">
                                    
                                    <div class="form-group">
                                        <label for="no_rm">Nomor Rekam Medis</label>
                                        <input type="text" class="form-control" name="no_rm_display" value="{{ $patient->no_rm }}" disabled>
                                        <input type="hidden" name="no_rm" value="{{ $patient->no_rm }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="noktp">No KTP</label>
                                        <input type="text" class="form-control" maxlength="16" inputmode="numeric" id="noktp" name="no_ktp" value="{{ $patient->no_ktp }}"/>
                                    </div>

                                    <div class="form-group">
                                        <label for="namapasien">Nama Pasien</label>
                                        <input type="text" name="nama_pasien" class="form-control" value="{{ $patient->nama_pasien }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="tanggal_lahir">Tanggal Lahir</label>
                                        <div class="input-group">
                                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" max="{{ date('Y-m-d') }}" value="{{ $patient->tanggal_lahir }}" onchange="hitungUmur()" required>
                                            <span class="input-group-text">Umur: {{ \Carbon\Carbon::parse($patient->tanggal_lahir)->age }} Tahun</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="jk">Jenis Kelamin</label>
                                        <select class="form-select form-control" name="jenis_kelamin" required>
                                            <option disabled hidden>Pilih Jenis Kelamin</option>
                                            <option value="Laki-laki" {{ $patient->jenis_kelamin=='Laki-laki'?'selected':'' }}>Laki-laki</option>
                                            <option value="Perempuan" {{ $patient->jenis_kelamin=='Perempuan'?'selected':'' }}>Perempuan</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="golongandarah">Golongan Darah</label>
                                        <select class="form-select form-control" name="golongan_darah" required>
                                            <option disabled hidden>Pilih Golongan Darah</option>
                                            <option value="A" {{ $patient->golongan_darah=='A'?'selected':'' }}>A</option>
                                            <option value="B" {{ $patient->golongan_darah=='B'?'selected':'' }}>B</option>
                                            <option value="AB" {{ $patient->golongan_darah=='AB'?'selected':'' }}>AB</option>
                                            <option value="O" {{ $patient->golongan_darah=='O'?'selected':'' }}>O</option>
                                            <option value="Tidak Tahu" {{ $patient->golongan_darah=='Tidak Tahu'?'selected':'' }}>Tidak Tahu</option>
                                        </select>
                                    </div>

                                    

                                    
                                    
                                </div> <!-- END KOLOM KIRI (PERBAIKAN: Penutup ini tadi hilang) -->


                                <!-- KOLOM TENGAH -->
                                <div class="col-md-6 col-lg-4">
                                    
                                    <div class="form-group">
                                        <label for="agama">Agama</label>
                                        <select class="form-select form-control" name="agama" required>
                                            <option disabled hidden>Pilih Agama</option>
                                            <option value="Islam" {{ $patient->agama=='Islam'?'selected':'' }}>Islam</option>
                                            <option value="Kristen" {{ $patient->agama=='Kristen'?'selected':'' }}>Kristen</option>
                                            <option value="Katolik" {{ $patient->agama=='Katolik'?'selected':'' }}>Katolik</option>
                                            <option value="Hindu" {{ $patient->agama=='Hindu'?'selected':'' }}>Hindu</option>
                                            <option value="Budha" {{ $patient->agama=='Budha'?'selected':'' }}>Budha</option>
                                            <option value="Konghucu" {{ $patient->agama=='Konghucu'?'selected':'' }}>Konghucu</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="nowa">No Telpon/WA</label>
                                        <input type="text" class="form-control" id="nowa" name="no_wa" inputmode="numeric" maxlength="13" value="{{ $patient->no_wa }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="pekerjaan">Pekerjaan</label>
                                        <select class="form-select form-control" name="pekerjaan" required>
                                            <option value="" disabled hidden>Pilih Pekerjaan</option>
                                            <option value="Pegawai BUMN" {{ $patient->pekerjaan=='Pegawai BUMN'?'selected':'' }}>Pegawai BUMN</option>
                                            <option value="PNS" {{ $patient->pekerjaan=='PNS'?'selected':'' }}>PNS</option>
                                            <option value="TNI" {{ $patient->pekerjaan=='TNI'?'selected':'' }}>TNI</option>
                                            <option value="Polisi" {{ $patient->pekerjaan=='Polisi'?'selected':'' }}>Polisi</option>
                                            <option value="Karyawan Swasta" {{ $patient->pekerjaan=='Karyawan Swasta'?'selected':'' }}>Karyawan Swasta</option>
                                            <option value="Petani" {{ $patient->pekerjaan=='Petani'?'selected':'' }}>Petani</option>
                                            <option value="Nelayan" {{ $patient->pekerjaan=='Nelayan'?'selected':'' }}>Nelayan</option>
                                            <option value="Wiraswasta" {{ $patient->pekerjaan=='Wiraswasta'?'selected':'' }}>Wiraswasta</option>
                                            <option value="Pelajar / Mahasiswa" {{ $patient->pekerjaan=='Pelajar / Mahasiswa'?'selected':'' }}>Pelajar / Mahasiswa</option>
                                            <option value="Ibu Rumah Tangga" {{ $patient->pekerjaan=='Ibu Rumah Tangga'?'selected':'' }}>Ibu Rumah Tangga</option>
                                            <option value="Tidak Bekerja" {{ $patient->pekerjaan=='Tidak Bekerja'?'selected':'' }}>Tidak Bekerja</option>
                                            <option value="Lainnya" {{ $patient->pekerjaan=='Lainnya'?'selected':'' }}>Lainnya</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="pendidikan">Pendidikan</label>
                                        <select class="form-select form-control" name="pendidikan" required>
                                            <option disabled hidden>Pilih Pendidikan Terakhir</option>
                                            <option value="Tidak Sekolah" {{ $patient->pendidikan=='Tidak Sekolah'?'selected':'' }}>Tidak Sekolah</option>
                                            <option value="Belum / Tidak Tamat SD" {{ $patient->pendidikan=='Belum / Tidak Tamat SD'?'selected':'' }}>Belum / Tidak Tamat SD</option>
                                            <option value="Tamat SD" {{ $patient->pendidikan=='Tamat SD'?'selected':'' }}>Tamat SD</option>
                                            <option value="Tamat SLTP / SMP/ Sederajat" {{ $patient->pendidikan=='Tamat SLTP / SMP/ Sederajat'?'selected':'' }}>Tamat SLTP / SMP/ Sederajat</option>
                                            <option value="Tamat SLTA / SMA / SMK / Sederajat" {{ $patient->pendidikan=='Tamat SLTA / SMA / SMK / Sederajat'?'selected':'' }}>Tamat SLTA / SMA / SMK / Sederajat</option>
                                            <option value="Diploma" {{ $patient->pendidikan=='Diploma'?'selected':'' }}>Diploma</option>
                                            <option value="Sarjana" {{ $patient->pendidikan=='Sarjana'?'selected':'' }}>Sarjana</option>
                                            <option value="Pasca Sarjana" {{ $patient->pendidikan=='Pasca Sarjana'?'selected':'' }}>Pasca Sarjana</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="Status_perkawinan">Status Perkawinan</label>
                                        <select class="form-select form-control" name="status_perkawinan" required>
                                            <option disabled hidden>Pilih Status Perkawinan</option>
                                            <option value="Belum Kawin" {{ $patient->status_perkawinan=='Belum Kawin'?'selected':'' }}>Belum Kawin</option>
                                            <option value="Kawin" {{ $patient->status_perkawinan=='Kawin'?'selected':'' }}>Kawin</option>
                                            <option value="Cerai Hidup" {{ $patient->status_perkawinan=='Cerai Hidup'?'selected':'' }}>Cerai Hidup</option>
                                            <option value="Cerai Mati" {{ $patient->status_perkawinan=='Cerai Mati'?'selected':'' }}>Cerai Mati</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="status">Status dalam Keluarga</label>
                                        <select class="form-select form-control" name="status_keluarga" required>
                                            <option disabled hidden>Pilih Status dalam Keluarga</option>
                                            <option value="Tuan" {{ $patient->status_keluarga=='Tuan'?'selected':'' }}>Tuan</option>
                                            <option value="Nyonya" {{ $patient->status_keluarga=='Nyonya'?'selected':'' }}>Nyonya</option>
                                            <option value="Anak" {{ $patient->status_keluarga=='Anak'?'selected':'' }}>Anak</option>
                                            <option value="Lainnya" {{ $patient->status_keluarga=='Lainnya'?'selected':'' }}>Lainnya</option>
                                        </select>
                                    </div>

                                    

                                </div> <!-- END KOLOM TENGAH -->

                                <!-- KOLOM KANAN -->
                                <div class="col-md-6 col-lg-4">                                    
                                    
                                    <div class="form-group">
                                        <label for="alamat">Alamat</label>
                                        <textarea class="form-control" name="alamat" required rows="2">{{ $patient->alamat }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="provinsi">Provinsi</label>
                                        <select class="form-select form-control" id="provinsi" name="provinsi_id" required>
                                            <option value="" disabled hidden>Pilih Provinsi</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="kabupaten">Kabupaten</label>
                                        <select class="form-select form-control" id="kabupaten" name="kabupaten_id" required>
                                            <option value="" disabled hidden>Pilih Kabupaten</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="kecamatan">Kecamatan</label>
                                        <select class="form-select form-control" id="kecamatan" name="kecamatan_id" required>
                                            <option value="" disabled hidden>Pilih Kecamatan</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="desa">Desa</label>
                                        <select class="form-select form-control" id="desa" name="desa_id" required>
                                            <option value="" disabled hidden>Pilih Desa</option>
                                        </select>
                                    </div>

                                </div> <!-- END KOLOM KANAN -->
                            </div> <!-- END ROW FORM -->
                        </div> <!-- END CARD BODY -->

                        <div class="card-action d-flex gap-2">
                            <button type="submit" class="btn btn-success">Update</button>
                            <a href="{{ route('master.pasien') }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    /*============ API WILAYAH ============*/
    const API = "https://www.emsifa.com/api-wilayah-indonesia/api/";
    let selected = {
        provinsi: "{{$patient->provinsi_id}}",
        kabupaten: "{{$patient->kabupaten_id}}",
        kecamatan: "{{$patient->kecamatan_id}}",
        desa: "{{$patient->desa_id}}"
    };

    // LOAD PROVINSI
    fetch(API + "provinces.json")
        .then(r => r.json())
        .then(d => {
            provinsi.innerHTML = `<option hidden>Pilih Provinsi</option>`;
            d.forEach(v => {
                provinsi.innerHTML += `<option value="${v.id}" ${v.id == selected.provinsi ? 'selected' : ''}>${v.name}</option>`;
            });
            if (selected.provinsi) loadKabupaten(selected.provinsi);
        });

    function loadKabupaten(id) {
        fetch(API + "regencies/" + id + ".json")
            .then(r => r.json())
            .then(d => {
                kabupaten.innerHTML = `<option hidden>Pilih Kabupaten</option>`;
                d.forEach(v => {
                    kabupaten.innerHTML += `<option value="${v.id}" ${v.id == selected.kabupaten ? 'selected' : ''}>${v.name}</option>`;
                });
                if (selected.kabupaten) loadKecamatan(selected.kabupaten);
            });
    }

    function loadKecamatan(id) {
        fetch(API + "districts/" + id + ".json")
            .then(r => r.json())
            .then(d => {
                kecamatan.innerHTML = `<option hidden>Pilih Kecamatan</option>`;
                d.forEach(v => {
                    kecamatan.innerHTML += `<option value="${v.id}" ${v.id == selected.kecamatan ? 'selected' : ''}>${v.name}</option>`;
                });
                if (selected.kecamatan) loadDesa(selected.kecamatan);
            });
    }

    function loadDesa(id) {
        fetch(API + "villages/" + id + ".json")
            .then(r => r.json())
            .then(d => {
                desa.innerHTML = `<option hidden>Pilih Desa</option>`;
                d.forEach(v => {
                    desa.innerHTML += `<option value="${v.id}" ${v.id == selected.desa ? 'selected' : ''}>${v.name}</option>`;
                });
            });
    }

    // EVENT CHAIN ON CHANGE
    provinsi.onchange = () => loadKabupaten(provinsi.value);
    kabupaten.onchange = () => loadKecamatan(kabupaten.value);
    kecamatan.onchange = () => loadDesa(kecamatan.value);

</script>
@endpush