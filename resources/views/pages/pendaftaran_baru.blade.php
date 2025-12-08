@extends('layout.app')

@section ('content')
        <div class="container">
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
                    <form action="{{ route('patient.store') }}" method="POST">
                      @csrf
                      {{-- Validation errors & flash messages --}}
                      @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
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

                      <div class="card-body">
                        <div class="row">
                          <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                              <label for="namapasien">Nama Pasien</label>
                              <input
                                type="text"
                                class="form-control"
                                name="nama_pasien"
                                required
                              />
                            </div>
                            <div class="form-group">
                              <label for="no_rm">Nomor Rekam Medis Terakhir</label>
                              <input type="text" 
                              class="form-control" 
                              name="no_rm_display" 
                              value="{{ $lastNoRm ?? 'Belum ada No RM' }}" disabled>
                              <input type="hidden" name="no_rm" value="{{ $lastNoRm }}">
                            </div>
                            <div class="form-group">
                              <label for="noktp">No KTP</label>
                              <input
                                type="text"
                                class="form-control"
                                maxlength="16"
                                inputmode="numeric"
                                id="noktp"
                                name="no_ktp"
                                required    
                              />
                            </div>
                            <div class="form-group">
                              <label for="agama">Agama</label>
                              <select
                                class="form-select form-control"
                                name="agama"
                                required
                              >
                              <option value="" disabled selected hidden>Pilih Agama</option>
                              <option value="Islam">Islam</option>
                              <option value="Kristen">Kristen</option>
                              <option value="Katolik">Katolik</option>
                              <option value="Hindu">Hindu</option>
                              <option value="Budha">Budha</option>
                              <option value="Konghucu">Konghucu</option>
                              </select>
                            </div>
                            <div class="form-group">
                              <label for="pendidikan">Pendidikan</label>
                              <select
                                class="form-select form-control"
                                name="pendidikan"
                                required
                              >
                              <option value="" disabled selected hidden>Pilih Pendidikan Terakhir</option>
                              <option value="Tidak Sekolah">Tidak Sekolah</option>
                              <option value="Belum / Tidak Tamat SD">Belum / Tidak Tamat SD</option>
                              <option value="Tamat SD">Tamat SD</option>
                              <option value="Tamat SLTP / SMP/ Sederajat">Tamat SLTP / SMP/ Sederajat</option>
                              <option value="Tamat SLTA / SMA / SMK / Sederajat">Tamat SLTA / SMA / SMK / Sederajat</option>
                              <option value="Diploma">Diploma</option>
                              <option value="Sarjana">Sarjana</option>
                              <option value="Pasca Sarjana">Pasca Sarjana</option>
                              </select>
                            </div>
                            <div class="form-group">
                              <label for="Status_perkawinan">Status Perkawinan</label>
                              <select
                                class="form-select form-control"
                                name="status_perkawinan"
                                required
                              >
                              <option value="" disabled selected hidden>Pilih Status Perkawinan</option>
                              <option value="Belum Kawin">Belum Kawin</option>
                              <option value="Kawin">Kawin</option>
                              <option value="Cerai Hidup">Cerai Hidup</option>
                              <option value="Cerai Mati">Cerai Mati</option>
                              </select>
                            </div>
                            <div class="form-group">
                              <label for="status">Status dalam Keluarga</label>
                              <select
                                class="form-select form-control"
                                name="status_keluarga"
                                required
                              >
                              <option value="" disabled selected hidden>Pilih Status dalam Keluarga</option>
                              <option value="Tuan">Tuan</option>
                              <option value="Nyonya">Nyonya</option>
                              <option value="Anak">Anak</option>
                              <option value="Lainnya">Lainnya</option>
                              </select>
                            </div>
                            <div class="form-group">
                              <label for="tanggal_lahir">Tanggal Lahir</label>
                                <div class="input-group mb-3">
                                  <input
                                    type="date"
                                    class="form-control"
                                    id="tanggal_lahir"        
                                    name="tanggal_lahir"          
                                    max="{{ date('Y-m-d') }}" 
                                    onchange="hitungUmur()"   
                                    required                  
                                  />
                                  <span class="input-group-text" id="umur_display">
                                    Umur: 0y 0m 0d
                                  </span>
                                </div>
                            </div>
                            <div class="form-group">
                              <label for="jk">Jenis Kelamin</label>
                              <select
                                class="form-select form-control"
                                name="jenis_kelamin"
                                required
                              >
                              <option value="" disabled selected hidden>Pilih Jenis Kelamin</option>
                              <option value="Laki-laki">Laki-laki</option>
                              <option value="Perempuan">Perempuan</option>
                              </select>
                            </div>
                            <div class="form-group">
                              <label for="golongandarah">Golongan Darah</label>
                              <select
                                class="form-select form-control"
                                name="golongan_darah"
                                required
                              >
                              <option value="" disabled selected hidden>Pilih Golongan Darah</option>
                              <option value="A">A</option>
                              <option value="B">B</option>
                              <option value="AB">AB</option>
                              <option value="O">O</option>
                              <option value="Tidak Tahu">Tidak Tahu</option>
                              </select>
                            </div>
                            <div class="form-group">
                              <label for="alamat">Alamat</label>
                              <textarea class="form-control" 
                              name="alamat"
                              required 
                              rows="2"></textarea>
                            </div>
                            <div class="form-group">
                              <label for="nowa">No Telpon/WA</label>
                              <input
                                type="text"
                                class="form-control"
                                id="nowa"
                                name="no_wa"
                                inputmode="numeric"
                                maxlength="13"
                                required
                              />
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                            <label for="pekerjaan">Pekerjaan</label>
                            <select
                              class="form-select form-control"
                              name="pekerjaan"
                              required
                            >
                            <option value="" disabled selected hidden>Pilih Pekerjaan</option>
                            <option value="Pegawai BUMN">Pegawai BUMN</option>
                            <option value="PNS">PNS</option>
                            <option value="TNI">TNI</option>
                            <option value="Polisi">Polisi</option>
                            <option value="Karyawan Swasta">Karyawan Swasta</option>
                            <option value="Petani">Petani</option>
                            <option value="Nelayan">Nelayan</option>
                            <option value="Wiraswasta">Wiraswasta</option>
                            <option value="Pelajar / Mahasiswa">Pelajar / Mahasiswa</option>
                            <option value="Ibu Rumah Tangga">Ibu Rumah Tangga</option>
                            <option value="Tidak Bekerja">Tidak Bekerja</option>
                            <option value="Lainnya">Lainnya</option>
                            </select>
                          </div>
                          <div class="form-group">
                            <label for="provinsi">Provinsi</label>
                            <select
                              class="form-select form-control"
                              id="provinsi"
                              name="provinsi_id"
                              required
                            >
                            <option value="" disabled selected hidden>Pilih Provinsi</option>
                            </select>
                          </div>
                          <div class="form-group">
                            <label for="kabupaten">Kabupaten</label>
                            <select
                              class="form-select form-control"
                              id="kabupaten"
                              name="kabupaten_id"
                              required
                            >
                            <option value="" disabled selected hidden>Pilih kabupaten</option>
                            <option>-</option>
                            </select>
                          </div>
                          <div class="form-group">
                            <label for="kecamatan">Kecamatan</label>
                            <select
                              class="form-select form-control"
                              id="kecamatan"
                              name="kecamatan_id"
                              required
                            >
                            <option value="" disabled selected hidden>Pilih Kecamatan</option>
                            <option>-</option>
                            </select>
                          </div>
                          <div class="form-group">
                            <label for="desa">desa</label>
                            <select
                              class="form-select form-control"
                              id="desa"
                              name="desa_id"
                              required
                            >
                            <option value="" disabled selected hidden>Pilih Desa</option>
                            <option>-</option>
                            </select>
                          </div>
                          <div class="form-group">
                            <label for="rujukan">Rujukan Dari</label>
                            <select
                              class="form-select form-control"
                              name="rujukan_dari"
                              required
                            >
                            <option value="" disabled selected hidden>Pilih Asal Rujukan</option>
                            <option value="Sendiri/Keluarga">Sendiri/Keluarga</option>
                            <option value="Masyarakat">Masyarakat</option>
                            <option value="Kader">Kader</option>
                            <option value="UKS">UKS</option>
                            <option value="Polindes">Polindes</option>
                            <option value="Kesehatan Lain">Kesehatan Lain</option>
                            <option value="Rumah Sakit">Rumah Sakit</option>
                            </select>
                          </div>
                          <div class="form-group">
                            <label for="keteranganrujukan">Keterangan Rujukan</label>
                            <input
                              type="text"
                              class="form-control"
                              name="keterangan_rujukan"
                              required
                            />
                          </div>
                          <div class="form-group">
                            <label for="tanggal_kunjungan">Tanggal Kunjungan</label>
                              <div class="input-group mb-3">
                                <input
                                  type="date"
                                  class="form-control"
                                  name="tanggal_kunjungan"          
                                  required                  
                                />
                              </div>
                          </div>
                            <div class="form-group">
                            <label for="tujuan">Tujuan</label>
                            <select
                              class="form-select form-control"
                              name="poli_tujuan"
                              required
                            >
                            <option value="" disabled selected hidden>Pilih Tujuan</option>
                            <option value="ugd">UGD</option>
                            <option value="umum">Klinik Umum</option>
                            <option value="rawat_inap">Rawat Inap</option>
                            </select>
                          </div>
                          <div class="form-group">
                            <label for="jadwal">Jadwal</label>
                            <select
                              class="form-select form-control"
                              name="jadwal_dokter"
                              required
                            >
                            <option value="" disabled selected hidden>Pilih Jadwal</option>
                            <option value="Klinik Umum - dr. Mikel  - 07.00-13.00">Klinik Umum - dr. Mikel  - 07.00-13.00</option>
                            <option value="Klinik Umum - dr. Jokowi - 14.00-20.00">Klinik Umum - dr. Jokowi - 14.00-20.00</option>
                            </select>
                          </div>
                          <div class="form-group">
                            <label for="kunjungan">Kunjungan</label>
                            <select
                              class="form-select form-control"
                              name="kunjungan"
                              required
                            >
                            <option value="" disabled selected hidden>Pilih Jenis Kunjungan</option>
                            <option value="Sakit">Sakit</option>
                            <option value="Sehat">Sehat</option>
                            </select>
                          </div>
                          <div class="form-group">
                            <label for="jenis_bayar">Jenis Pembayaran</label>
                            <select
                              class="form-select form-control"
                              name="jenis_bayar"
                              id="jenis_bayar"
                              required
                              onchange="toggleNoAsuransi()"
                            >
                              <option value="" disabled selected hidden>Pilih Jenis Pembayaran</option>
                              <option value="Umum">Umum</option>
                              <option value="BPJS PBI">BPJS PBI</option>
                              <option value="BPJS NON PBI">BPJS NON PBI</option>
                              <option value="JAMKESDA">JAMKESDA</option>
                            </select>
                          </div>
                          <div class="form-group" id="no_asuransi_group" style="display: none;">
                            <label for="no_asuransi">No Asuransi</label>
                            <input
                              type="text"
                              class="form-control"
                              name="no_asuransi"
                              id="no_asuransi"
                              oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            />
                          </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                            <label for="namapenanggungjawabpasien">Nama Penanggungjawab Pasien</label>
                            <input
                              type="text"
                              class="form-control"
                              name="pj_nama"
                              
                            />
                          </div>
                          <div class="form-group">
                            <label for="noktppenanggungjawab">No KTP Penanggungjawab</label>
                            <input
                              type="text"
                              class="form-control"
                              maxlength="16"
                              inputmode="numeric"
                              id="noktppenanggungjawab"
                              name="pj_no_ktp"
                                  
                            />
                          </div>
                          <div class="form-group">
                            <label for="alamatpenanggungjawab">Alamat Lengkap Penanggungjawab</label>
                            <textarea 
                            class="form-control"
                            id="alamatpenanggungjawab" 
                            name="pj_alamat" 
                            rows="2"></textarea>
                          </div>
                          <div class="form-group">
                            <label for="nowapenanggungjawab">No Telpon/WA Penanggungjawab</label>
                            <input
                              type="text"
                              class="form-control"
                              id="nowapenanggungjawab"
                              name="pj_no_wa"
                              inputmode="numeric"
                              maxlength="13"
                              
                            />
                          </div>
                          <div class="form-group">
                            <label for="catatan">Catatan</label>
                            <textarea 
                            class="form-control" 
                            name="catatan_kunjungan" 
                            rows="3"
                            placeholder="Silakan isi catatan Anda di sini (misalnya: alergi obat, permintaan khusus)."
                            ></textarea>
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
              </div>
            </div>
          </div>
        </div>
@endsection
@push('scripts')
<script>
// 1. KONFIGURASI API WILAYAH (API Publik Indonesia) — handled by shared partial

// --- FUNGSI UTILITAS LAMA ANDA ---

function cleanAndLimitKTP(event) {
    let value = event.target.value;
    // Hapus karakter non-angka
    value = value.replace(/[^0-9]/g, ''); 
    // Batasi hingga 16 digit
    value = value.slice(0, 16); 
    event.target.value = value;
}

function cleanAndLimitWAInput(event) {
    let value = event.target.value;
    // Hapus karakter non-angka
    value = value.replace(/[^0-9]/g, '');
    // Batasi hingga 13 digit
    value = value.slice(0, 13); 
    event.target.value = value;
}

function hitungUmur() {
    const tanggalLahirInput = document.getElementById('tanggal_lahir');
    const displayUmur = document.getElementById('umur_display');
    const tanggalLahirValue = tanggalLahirInput ? tanggalLahirInput.value : null;

    if (!tanggalLahirValue) {
        if (displayUmur) displayUmur.textContent = "Umur: 0y 0m 0d";
        return;
    }

    const tglLahir = new Date(tanggalLahirValue);
    const hariIni = new Date();

    if (tglLahir > hariIni) {
        if (displayUmur) displayUmur.textContent = "Umur: Tanggal Tidak Valid";
        return;
    }

    let tahun = hariIni.getFullYear() - tglLahir.getFullYear();
    let bulan = hariIni.getMonth() - tglLahir.getMonth();
    let hari = hariIni.getDate() - tglLahir.getDate();

    if (hari < 0) {
        // Ambil jumlah hari di bulan sebelumnya
        bulan--;
        hari += new Date(hariIni.getFullYear(), hariIni.getMonth(), 0).getDate(); 
    }

    if (bulan < 0) {
        tahun--;
        bulan += 12;
    }
    
    if (displayUmur) displayUmur.textContent = `Umur: ${tahun}y ${bulan}m ${hari}d`;
}

document.addEventListener('DOMContentLoaded', function () {
  const jenisBayarSelect = document.getElementById('jenis_bayar');
  if (jenisBayarSelect) {
    jenisBayarSelect.addEventListener('change', toggleNoAsuransi);
  }
});
function toggleNoAsuransi() {
  const jenisBayar = document.getElementById("jenis_bayar").value;
  const noAsuransiGroup = document.getElementById("no_asuransi_group");
  const noAsuransiInput = document.getElementById("no_asuransi");

  if (!noAsuransiGroup || !noAsuransiInput) return; // cegah error

  if (jenisBayar === "BPJS PBI" || jenisBayar === "BPJS NON PBI") {
    noAsuransiGroup.style.display = "block";
    noAsuransiInput.required = true;
  } else {
    noAsuransiGroup.style.display = "none";
    noAsuransiInput.required = false;
    noAsuransiInput.value = ""; // hapus isi jika disembunyikan
  }
}
// --- FUNGSI BARU UNTUK DROPDOWN WILAYAH ---
// wilayah JS moved to partial
</script>
@include('partials.wilayah_scripts')
@endpush