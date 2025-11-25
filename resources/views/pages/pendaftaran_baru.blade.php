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
                            <option value="UGD">UGD</option>
                            <option value="KLinik Umum">Klinik Umum</option>
                            <option value="Rawat Inap">Rawat Inap</option>
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
// 1. KONFIGURASI API WILAYAH (API Publik Indonesia)
const BASE_URL_WILAYAH = 'https://www.emsifa.com/api-wilayah-indonesia/api/';

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

/**
 * Fungsi utilitas untuk mengambil data dari API dan mengisi dropdown.
 */
async function fetchAndFillDropdown(url, dropdownId, placeholderText = null) {
    const selectElement = document.getElementById(dropdownId);
    
    if (!selectElement) {
        console.error(`Elemen dengan ID '${dropdownId}' tidak ditemukan.`);
        return;
    }

    // Nonaktifkan dan tampilkan status loading
    selectElement.disabled = true;
    selectElement.innerHTML = `<option value="" disabled selected hidden>Memuat ${placeholderText}...</option>`;
    
    // Reset dropdown setelahnya (untuk Kabupaten, Kecamatan, Desa)
    const nextDropdowns = ['provinsi', 'kabupaten', 'kecamatan', 'desa'];
    const currentIndex = nextDropdowns.indexOf(dropdownId);

    // Reset dropdown di level yang lebih rendah
    if (currentIndex >= 0) {
        for (let i = currentIndex + 1; i < nextDropdowns.length; i++) {
            const nextSelect = document.getElementById(nextDropdowns[i]);
            if (nextSelect) {
                const name = nextDropdowns[i].charAt(0).toUpperCase() + nextDropdowns[i].slice(1);
                nextSelect.innerHTML = `<option value="" disabled selected hidden>Pilih ${name}</option><option value="-">-</option>`;
                nextSelect.disabled = true;
            }
        }
    }

    try {
        const response = await fetch(url);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        // Isi dropdown dengan data baru
        selectElement.innerHTML = `<option value="" disabled selected hidden>${placeholderText || 'Pilih'}</option>`;
        
        // Opsi '-' untuk level non-provinsi
        if (dropdownId !== 'provinsi') {
            selectElement.innerHTML += '<option value="-">-</option>';
        }

        data.forEach(item => {
            const option = document.createElement('option');
            // Menggunakan ID sebagai value dan Nama sebagai teks
            option.value = item.id; 
            option.textContent = item.name;
            selectElement.appendChild(option);
        });
        selectElement.disabled = false; // Aktifkan kembali
    } catch (error) {
        console.error(`Error fetching data for ${dropdownId}:`, error);
        selectElement.innerHTML = `<option value="" disabled selected hidden>Gagal memuat data</option>`;
        // Biarkan disabled jika gagal
    }
}

// --- INISIALISASI SEMUA FUNGSI SAAT DOKUMEN SIAP ---

document.addEventListener('DOMContentLoaded', function() {
    
    // SETUP CLEANERS & LISTENERS KTP/WA/UMUR
    const inputKTP_Pasien = document.getElementById('noktp');
    const inputKTP_PJ = document.getElementById('noktppenanggungjawab');
    if (inputKTP_Pasien) inputKTP_Pasien.addEventListener('input', cleanAndLimitKTP);
    if (inputKTP_PJ) inputKTP_PJ.addEventListener('input', cleanAndLimitKTP);

    const inputWA = document.getElementById('nowa');
    const inputWAPJ = document.getElementById('nowapenanggungjawab'); 
    if (inputWA) inputWA.addEventListener('input', cleanAndLimitWAInput);
    if (inputWAPJ) inputWAPJ.addEventListener('input', cleanAndLimitWAInput);

    const tanggalLahirInput = document.getElementById('tanggal_lahir');
    if (tanggalLahirInput) {
        tanggalLahirInput.addEventListener('change', hitungUmur);
        // Panggil saat dimuat untuk menampilkan umur default
        hitungUmur(); 
    }
    
    // PENGISIAN DROPDOWN WILAYAH DINAMIS

    // 1. Inisialisasi Provinsi
    fetchAndFillDropdown(`${BASE_URL_WILAYAH}provinces.json`, 'provinsi', 'Pilih Provinsi');

    // 2. Listener Provinsi -> Kabupaten
    const provinsiSelect = document.getElementById('provinsi');
    if (provinsiSelect) {
        provinsiSelect.addEventListener('change', function() {
            const selectedProvinceId = this.value;
            if (selectedProvinceId && selectedProvinceId !== '-') {
                fetchAndFillDropdown(
                    `${BASE_URL_WILAYAH}regencies/${selectedProvinceId}.json`, 
                    'kabupaten', 
                    'Pilih Kabupaten'
                );
            }
        });
    }

    // 3. Listener Kabupaten -> Kecamatan
    const kabupatenSelect = document.getElementById('kabupaten');
    if (kabupatenSelect) {
        kabupatenSelect.addEventListener('change', function() {
            const selectedRegencyId = this.value;
            if (selectedRegencyId && selectedRegencyId !== '-') {
                fetchAndFillDropdown(
                    `${BASE_URL_WILAYAH}districts/${selectedRegencyId}.json`, 
                    'kecamatan', 
                    'Pilih Kecamatan'
                );
            }
        });
    }
    
    // 4. Listener Kecamatan -> Desa
    const kecamatanSelect = document.getElementById('kecamatan');
    if (kecamatanSelect) {
        kecamatanSelect.addEventListener('change', function() {
            const selectedDistrictId = this.value;
            if (selectedDistrictId && selectedDistrictId !== '-') {
                fetchAndFillDropdown(
                    `${BASE_URL_WILAYAH}villages/${selectedDistrictId}.json`, 
                    'desa', 
                    'Pilih Desa'
                );
            }
        });
    }
});
</script>
@endpush