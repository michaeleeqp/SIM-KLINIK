@extends('layout.app')

@section('content')
<div class="container">
  <div class="page-inner">
    <div class="page-header">
      <h3 class="fw-bold mb-3">Pendaftaran</h3>
      <ul class="breadcrumbs mb-3">
        <li class="nav-home"><a href="#"><i class="fas fa-layer-group"></i></a></li>
        <li class="separator"><i class="icon-arrow-right"></i></li>
        <li class="nav-item"><a href="#">Pendaftaran</a></li>
        <li class="separator"><i class="icon-arrow-right"></i></li>
        <li class="nav-item"><a href="#">Pasien Lama</a></li>
      </ul>
    </div>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="card-title">Pendaftaran Pasien Lama</div>
          </div>

          <!-- FORM KUNJUNGAN PASIEN LAMA -->
          <form action="{{ route('kunjungan.store') }}" method="POST" id="formKunjungan">
            @csrf
            <!-- patient_id wajib -->
            <input type="hidden" name="patient_id" id="patient_id">

            <div class="card-body">
              <div class="row">
                <!-- KOLOM 1 -->
                <div class="col-md-6 col-lg-4">
                  <div class="form-group">
                    <label for="no_rm">No RM / NIK Pasien</label>
                    <div class="input-group">
                      <input type="text" class="form-control" id="no_rm" placeholder="Masukkan No RM atau NIK">
                      <button type="button" class="btn btn-primary" id="btnCariPasien">Cari</button>
                    </div>
                  </div>

                  <div class="form-group">
                    <label>Nama Pasien</label>
                    <input type="text" class="form-control" name="nama_pasien" id="nama_pasien" readonly>
                  </div>

                  <div class="form-group">
                    <label>No KTP</label>
                    <input type="text" class="form-control" name="no_ktp" id="no_ktp" maxlength="16" readonly>
                  </div>

                  <div class="form-group">
                    <label>Agama</label>
                    <input type="text" class="form-control" name="agama" id="agama" readonly>
                  </div>

                  <div class="form-group">
                    <label>Pendidikan</label>
                    <input type="text" class="form-control" name="pendidikan" id="pendidikan" readonly>
                  </div>

                  <div class="form-group">
                    <label>Status Perkawinan</label>
                    <input type="text" class="form-control" name="status_perkawinan" id="status_perkawinan" readonly>
                  </div>

                  <div class="form-group">
                    <label>Status Keluarga</label>
                    <input type="text" class="form-control" name="status_keluarga" id="status_keluarga" readonly>
                  </div>

                  <div class="form-group">
                    <label>Tanggal Lahir</label>
                    <input type="date" 
                    class="form-control" 
                    name="tanggal_lahir" 
                    id="tanggal_lahir" 
                    readonly>
                    <span id="umur_display" 
                    class="form-text text-muted">Umur: -</span>
                  </div>

                  <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <input type="text" class="form-control" name="jenis_kelamin" id="jenis_kelamin" readonly>
                  </div>

                  <div class="form-group">
                    <label>Golongan Darah</label>
                    <input type="text" class="form-control" name="golongan_darah" id="golongan_darah" readonly>
                  </div>

                  <div class="form-group">
                    <label>Alamat</label>
                    <textarea class="form-control" name="alamat" id="alamat" rows="2" readonly></textarea>
                  </div>

                  <div class="form-group">
                    <label>No WA</label>
                    <input type="text" class="form-control" name="no_wa" id="no_wa" readonly>
                  </div>
                </div>

                <!-- KOLOM 2 -->
                <div class="col-md-6 col-lg-4">
                  <div class="form-group">
                    <label>Pekerjaan</label>
                    <input type="text" class="form-control" name="pekerjaan" id="pekerjaan" readonly>
                  </div>

                  <div class="form-group">
                      <label>Provinsi</label>
                      <select id="provinsi" name="provinsi_id" class="form-control"disabled></select>                      </select>
                  </div>

                  <div class="form-group">
                    <label>Kabupaten</label>
                    <select id="kabupaten" name="kabupaten_id" class="form-control" disabled></select>
                  </div>

                  <div class="form-group">
                    <label>Kecamatan</label>
                    <select id="kecamatan" name="kecamatan_id" class="form-control" disabled></select>
                  </div>

                  <div class="form-group">
                    <label>Desa</label>
                    <select id="desa" name="desa_id" class="form-control" disabled></select>
                  </div>

                  <div class="form-group">
                    <label>Rujukan Dari</label>
                    <select class="form-control" name="rujukan_dari" required>
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
                    <label>Keterangan Rujukan</label>
                    <input type="text" class="form-control" name="keterangan_rujukan" required>
                  </div>

                  <div class="form-group">
                    <label>Tanggal Kunjungan</label>
                    <input type="date" class="form-control" name="tanggal_kunjungan" required>
                  </div>

                  <div class="form-group">
                    <label>Tujuan</label>
                    <select class="form-control" name="poli_tujuan" required>
                      <option value="" disabled selected hidden>Pilih Tujuan</option>
                      <option value="UGD">UGD</option>
                      <option value="Klinik Umum">Klinik Umum</option>
                      <option value="Rawat Inap">Rawat Inap</option>
                    </select>
                  </div>

                  <div class="form-group">
                    <label>Jadwal</label>
                    <select class="form-control" name="jadwal_dokter" required>
                      <option value="" disabled selected hidden>Pilih Jadwal</option>
                      <option value="Klinik Umum - dr. Mikel  - 07.00-13.00">Klinik Umum - dr. Mikel  - 07.00-13.00</option>
                      <option value="Klinik Umum - dr. Jokowi - 14.00-20.00">Klinik Umum - dr. Jokowi - 14.00-20.00</option>
                    </select>
                  </div>

                  <div class="form-group">
                    <label>Kunjungan</label>
                    <select class="form-control" name="kunjungan" required>
                      <option value="" disabled selected hidden>Pilih Jenis Kunjungan</option>
                      <option value="Sakit">Sakit</option>
                      <option value="Sehat">Sehat</option>
                    </select>
                  </div>

                  <div class="form-group">
                    <label>Jenis Pembayaran</label>
                    <select class="form-control" name="jenis_bayar" id="jenis_bayar" onchange="toggleNoAsuransi()" required>
                      <option value="" disabled selected hidden>Pilih Jenis Pembayaran</option>
                      <option value="Umum">Umum</option>
                      <option value="BPJS PBI">BPJS PBI</option>
                      <option value="BPJS NON PBI">BPJS NON PBI</option>
                      <option value="JAMKESDA">JAMKESDA</option>
                    </select>
                  </div>

                  <div class="form-group" id="no_asuransi_group" style="display:none;">
                    <label>No Asuransi</label>
                    <input type="text" class="form-control" name="no_asuransi" id="no_asuransi">
                  </div>
                </div>

                <!-- KOLOM 3 -->
                <div class="col-md-6 col-lg-4">
                  <div class="form-group">
                    <label>Nama Penanggungjawab</label>
                    <input type="text" class="form-control" name="pj_nama" >
                  </div>

                  <div class="form-group">
                    <label>No KTP Penanggungjawab</label>
                    <input type="text" class="form-control" name="pj_no_ktp" id="pj_no_ktp" maxlength="16" >
                  </div>

                  <div class="form-group">
                    <label>Alamat Penanggungjawab</label>
                    <textarea class="form-control" name="pj_alamat" rows="2"></textarea>
                  </div>

                  <div class="form-group">
                    <label>No WA Penanggungjawab</label>
                    <input type="text" class="form-control" name="pj_no_wa" id="pj_no_wa" maxlength="13" >
                  </div>

                  <div class="form-group">
                    <label>Catatan</label>
                    <textarea class="form-control" name="catatan_kunjungan" rows="3"></textarea>
                  </div>
                </div>

              </div>
            </div>

            <div class="card-action d-flex gap-2">
              <button type="submit" class="btn btn-success">Submit</button>
              <button type="reset" class="btn btn-danger" id="btnReset">Cancel</button>
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
/* ============ KONFIGURASI ============ */
const BASE_URL_WILAYAH = 'https://www.emsifa.com/api-wilayah-indonesia/api/';

/* ======= HELPERS ======= */
function onlyDigitsAndLimit(el, maxLen) {
  el.value = el.value.replace(/[^0-9]/g, '').slice(0, maxLen || 999);
}

function toggleNoAsuransi() {
    const jenis = document.getElementById('jenis_bayar').value;
    const group = document.getElementById('no_asuransi_group');
    const input = document.getElementById('no_asuransi');

    if (!group || !input) return;

    // ❗ No Asuransi hanya muncul kalau TIDAK memilih Umum
    if (jenis !== 'Umum' && jenis !== '') {
        group.style.display = 'block';
        input.required = true;
    } else {
        group.style.display = 'none';
        input.required = false;
        input.value = '';
    }
}

function hitungUmurFromInput(dateStr) {
  const display = document.getElementById('umur_display');
  if (!display) return;
  if (!dateStr) { display.textContent = 'Umur: -'; return; }
  const tgl = new Date(dateStr);
  const now = new Date();
  if (tgl > now) { display.textContent = 'Umur: Tanggal Tidak Valid'; return; }

  let y = now.getFullYear() - tgl.getFullYear();
  let m = now.getMonth() - tgl.getMonth();
  let d = now.getDate() - tgl.getDate();

  if (d < 0) { m--; d += new Date(now.getFullYear(), now.getMonth(), 0).getDate(); }
  if (m < 0) { y--; m += 12; }
  display.textContent = `Umur: ${y}y ${m}m ${d}d`;
}

/* ======= SET DROPDOWN VALUE OTOMATIS ======= */
async function setSelectValue(selectId, value) {
  if (!value) return;
  const sel = document.getElementById(selectId);
  if (!sel) return;
  
  let attempts = 0;
  const timer = setInterval(() => {
    attempts++;
    if (Array.from(sel.options).some(o => o.value == value) || attempts > 40) {
      sel.value = value;
      clearInterval(timer);
    }
  }, 100);
}

/* ======= CARI PASIEN + ISI FORM ======= */
async function cariDanIsiPasien(query) {
  if (!query) return null;
  try {
    const res = await fetch(`/pasien/cari?q=${encodeURIComponent(query)}`);
    if (!res.ok) throw new Error('Server error ' + res.status);
    const json = await res.json();

    if (!json.success || !json.data) {
      alert('Pasien tidak ditemukan');
      return null;
    }

    const p = json.data;

    // set patient_id
    document.getElementById('patient_id').value = p.id || '';

    // isi semua field readonly
    const fields = [
      'nama_pasien','no_ktp','agama','pendidikan','status_perkawinan','status_keluarga',
      'jenis_kelamin','golongan_darah','alamat','no_wa','pekerjaan'
    ];
    fields.forEach(f => {
      const el = document.getElementById(f);
      if (el) el.value = p[f] || '';
    });

    // tanggal lahir & umur
    if (p.tanggal_lahir) {
      const tglEl = document.getElementById('tanggal_lahir');
      if (tglEl) {
        tglEl.value = p.tanggal_lahir;
        hitungUmurFromInput(p.tanggal_lahir);
      }
    }

    // dropdown wilayah
    if (p.provinsi_id) await fetchAndFillDropdown(`${BASE_URL_WILAYAH}provinces.json`, 'provinsi', 'Pilih Provinsi', p.provinsi_id);
    if (p.kabupaten_id) await fetchAndFillDropdown(`${BASE_URL_WILAYAH}regencies/${p.provinsi_id}.json`, 'kabupaten', 'Pilih Kabupaten', p.kabupaten_id);
    if (p.kecamatan_id) await fetchAndFillDropdown(`${BASE_URL_WILAYAH}districts/${p.kabupaten_id}.json`, 'kecamatan', 'Pilih Kecamatan', p.kecamatan_id);
    if (p.desa_id) await fetchAndFillDropdown(`${BASE_URL_WILAYAH}villages/${p.kecamatan_id}.json`, 'desa', 'Pilih Desa', p.desa_id);

    alert('Data pasien ditemukan dan diisi otomatis ✅');

  } catch (err) {
    console.error(err);
    alert('Terjadi kesalahan saat mencari pasien. Lihat console.');
  }
}

/* ======= FETCH DAN FILL DROPDOWN ======= */
async function fetchAndFillDropdown(url, selectId, placeholder, selectedValue=null) {
  try {
    const res = await fetch(url);
    if (!res.ok) throw new Error('Gagal load ' + selectId);
    const data = await res.json();
    const sel = document.getElementById(selectId);
    if (!sel) return;

    sel.innerHTML = `<option value="" disabled selected hidden>${placeholder}</option>`;
    data.forEach(d => {
      sel.innerHTML += `<option value="${d.id}">${d.name}</option>`;
    });

    if (selectedValue) sel.value = selectedValue;
  } catch (err) {
    console.error(err);
  }
}

/* ======= INIT ======= */
document.addEventListener('DOMContentLoaded', function() {
  // input cleaners
  const noKtpEl = document.getElementById('no_ktp');
  if (noKtpEl) noKtpEl.addEventListener('input', e => onlyDigitsAndLimit(e.target, 16));
  const noWaEl = document.getElementById('no_wa');
  if (noWaEl) noWaEl.addEventListener('input', e => onlyDigitsAndLimit(e.target, 13));
  const pjNo = document.getElementById('pj_no_ktp');
  if (pjNo) pjNo.addEventListener('input', e => onlyDigitsAndLimit(e.target, 16));
  const pjWa = document.getElementById('pj_no_wa');
  if (pjWa) pjWa.addEventListener('input', e => onlyDigitsAndLimit(e.target, 13));

  // load provinsi awal
  fetchAndFillDropdown(`${BASE_URL_WILAYAH}provinces.json`, 'provinsi', 'Masukkan nomor RM / NIK').catch(()=>{});

  // chaining dropdowns
  document.getElementById('provinsi').addEventListener('change', function() {
    const id = this.value;
    if (!id || id === '-') return;
    fetchAndFillDropdown(`${BASE_URL_WILAYAH}regencies/${id}.json`, 'kabupaten', 'Pilih Kabupaten').catch(()=>{});
  });
  document.getElementById('kabupaten').addEventListener('change', function() {
    const id = this.value;
    if (!id || id === '-') return;
    fetchAndFillDropdown(`${BASE_URL_WILAYAH}districts/${id}.json`, 'kecamatan', 'Pilih Kecamatan').catch(()=>{});
  });
  document.getElementById('kecamatan').addEventListener('change', function() {
    const id = this.value;
    if (!id || id === '-') return;
    fetchAndFillDropdown(`${BASE_URL_WILAYAH}villages/${id}.json`, 'desa', 'Pilih Desa').catch(()=>{});
  });

  // tombol cari pasien
  document.getElementById('btnCariPasien').addEventListener('click', async function() {
    const q = document.getElementById('no_rm').value.trim();
    if (!q) return alert('Masukkan No RM atau NIK dulu!');
    await cariDanIsiPasien(q);
  });

  // hitung umur saat manual ganti tanggal
  const tglL = document.getElementById('tanggal_lahir');
  if (tglL) tglL.addEventListener('change', () => hitungUmurFromInput(tglL.value));

  // toggle asuransi saat load
  toggleNoAsuransi();

  // reset: hapus patient_id
  document.getElementById('btnReset').addEventListener('click', function() {
    document.getElementById('patient_id').value = '';
  });
});
</script>
@endpush
