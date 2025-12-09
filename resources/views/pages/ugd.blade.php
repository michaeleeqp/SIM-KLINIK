@extends('layout.app')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3"> UGD — Asuhan Medis</h3>
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
                    <a href="#">Poliklinik UGD</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Form Poliklinik UGD</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <!-- Card Identitas Pasien -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Cari Pasien (No RM)</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" id="no_rm" name="no_rm" placeholder="Masukkan No RM">
                                    <button type="button" id="btnSearchRm" class="btn btn-primary">Cari</button>
                                </div>
                            </div>
                        </div>

                        <hr>

                        {{-- Filter tanggal / hari --}}
                        <form method="GET" class="mb-3">
                            <div class="row g-2 align-items-center">
                                <div class="col-auto">
                                    <input type="date" name="date" class="form-control" value="{{ $date ?? '' }}">
                                </div>
                                
                                <div class="col-auto">
                                    <button class="btn btn-sm btn-primary">Filter</button>
                                    <a href="{{ url()->current() }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                                </div>
                            </div>
                        </form>

                        {{-- Daftar kunjungan (pilih pasien) --}}
                        @if(isset($kunjungans) && $kunjungans->isNotEmpty())
                            <h5 class="mb-3">Daftar Kunjungan (Pilih pasien)</h5>
                            <div class="table-responsive mb-3">
                                <table class="table table-sm table-hover" id="kunjunganTable">
                                    <thead>
                                        <tr>
                                            <th>No RM</th>
                                            <th>Nama Pasien</th>
                                            <th>Tanggal Kunjungan</th>
                                            <th>Poli</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($kunjungans as $k)
                                            @php $p = $k->patient; @endphp
                                            @if($p)
                                                <tr>
                                                    <td>{{ $p->no_rm }}</td>
                                                    <td>{{ $p->nama_pasien }}</td>
                                                    <td>{{ optional($k->tanggal_kunjungan)->format('d-m-Y') ?? $k->tanggal_kunjungan }}</td>
                                                    <td>{{ ucfirst(str_replace('_', ' ', $k->poli_tujuan)) }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-outline-primary btn-select-kunjungan"
                                                            data-patient-id="{{ $p->id }}"
                                                            data-no-rm="{{ $p->no_rm }}"
                                                            data-nama="{{ $p->nama_pasien }}"
                                                            data-tanggal-lahir="{{ $p->tanggal_lahir ? $p->tanggal_lahir->format('Y-m-d') : '' }}"
                                                            data-jenis-kelamin="{{ $p->jenis_kelamin }}"
                                                            data-golongan-darah="{{ $p->golongan_darah }}">
                                                            Pilih
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        <h5>Identitas Pasien</h5>
                        <input type="hidden" id="patient_id" name="patient_id">
                        
                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>No RM</label>
                                    <input type="text" class="form-control" id="display_no_rm" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Nama Pasien</label>
                                    <input type="text" class="form-control" id="nama_pasien" readonly>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label for="tanggal_lahir">Tanggal Lahir</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control" id="tanggal_lahir" readonly>
                                        <span class="input-group-text" id="umur_display">Umur: -</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Jenis Kelamin</label>
                                    <input type="text" class="form-control" id="jenis_kelamin" readonly>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Golongan Darah</label>
                                    <input type="text" class="form-control" id="golongan_darah" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Asuhan Medis -->
                <div class="card mt-3">
                    <div class="card-header">
                        <div class="card-title">Asuhan Medis</div>
                    </div>
                    
                    <form id="poliklinikForm" action="{{ route('poliklinik.assessments.store', ['poli' => 'ugd']) }}" method="POST">
                        @csrf
                        <input type="hidden" name="no_rm" id="form_no_rm" value="">
                        <input type="hidden" id="patient_id">
                        
                        <div class="card-body">
                            <div class="form-group">
                                <label for="keluhan_utama">Keluhan Utama</label>
                                <input type="text" class="form-control" id="keluhan_utama" name="keluhan_utama" required>
                            </div>

                            <div class="form-group">
                                <label for="riwayat_penyakit">Riwayat Penyakit</label>
                                <input type="text" class="form-control" id="riwayat_penyakit" name="riwayat_penyakit">
                            </div>

                            <div class="form-group">
                                <label for="riwayat_alergi">Riwayat Alergi</label>
                                <input type="text" class="form-control" id="riwayat_alergi" name="riwayat_alergi">
                            </div>

                            <div class="form-group">
                                <label for="diagnosa_medis">Diagnosa Medis</label>
                                <input type="text" class="form-control" id="diagnosa_medis" name="diagnosa_medis" required>
                            </div>

                            <div class="form-group">
                                <label for="tindakan_terapi">Tindakan / Terapi</label>
                                <input type="text" class="form-control" id="tindakan_terapi" name="tindakan_terapi">
                            </div>

                            <div class="form-group">
                                <label for="catatan_perawatan">Catatan Perawatan</label>
                                <textarea class="form-control" id="catatan_perawatan" name="catatan_perawatan" rows="3"></textarea>
                            </div>

                            <h5 class="fw-bold mb-3 border-bottom pb-2 mt-4">Tanda-Tanda Vital</h5>

                            <div class="row">
                                <div class="col-12 col-md-6 col-lg-3">
                                    <div class="form-group">
                                        <label for="tekanan_darah">Tekanan Darah (mm/Hg)</label>
                                        <input type="text" class="form-control" id="tekanan_darah" 
       name="tekanan_darah" placeholder="120/80" 
       required
       oninput="this.value = this.value.replace(/[^0-9\/]/g, '')">

                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-3">
                                    <div class="form-group">
                                        <label for="nadi">Nadi (X/Menit)</label>
                                        <input type="number" class="form-control" id="nadi" name="nadi" placeholder="80" required inputmode="numeric" pattern="\d*" min="1" max="300">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-3">
                                    <div class="form-group">
                                        <label for="suhu">Suhu (°C)</label>
                                        <input type="number" step="0.1" class="form-control" id="suhu" name="suhu" placeholder="36.5" required>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-3">
                                    <div class="form-group">
                                        <label for="respirasi">Respirasi (X/Menit)</label>
                                        <input type="number" class="form-control" id="respirasi" name="respirasi" placeholder="20" required inputmode="numeric">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-action d-flex gap-2">
                            <button type="submit" class="btn btn-success">Simpan Asuhan</button>
                            <button type="button" class="btn btn-danger" id="btnReset">Reset</button>
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
// Validasi form submit
document.getElementById('poliklinikForm').addEventListener('submit', function(e) {
    const noRm = document.getElementById('form_no_rm').value.trim();
    if (!noRm) {
        e.preventDefault();
        alert('Silakan cari pasien terlebih dahulu dengan memasukkan No RM');
        return false;
    }
});

// Fungsi untuk mencari pasien berdasarkan No RM
document.getElementById('btnSearchRm').addEventListener('click', async function () {
    const noRm = document.getElementById('no_rm').value.trim();
    
    if (!noRm) {
        alert('Masukkan No RM terlebih dahulu');
        return;
    }

    try {
        const res = await fetch(`/pasien/cari?q=${encodeURIComponent(noRm)}`);
        
        if (!res.ok) {
            alert('Pasien tidak ditemukan');
            return;
        }

        const json = await res.json();
        
        if (!json.success) {
            alert('Pasien tidak ditemukan');
            return;
        }

        const p = json.data;
        
        // Isi data pasien
        document.getElementById('patient_id').value = p.id;
        document.getElementById('form_no_rm').value = p.no_rm || noRm;
        document.getElementById('display_no_rm').value = p.no_rm || noRm;
        document.getElementById('nama_pasien').value = p.nama_pasien || '';
        document.getElementById('tanggal_lahir').value = p.tanggal_lahir || '';
        document.getElementById('jenis_kelamin').value = p.jenis_kelamin || '';
        document.getElementById('golongan_darah').value = p.golongan_darah || '';
        
        // Hitung umur
        hitungUmur();
        
    } catch (err) {
        console.error(err);
        alert('Gagal mencari pasien. Cek koneksi atau lihat log.');
    }
});

// Fungsi untuk menghitung umur
function hitungUmur() {
    const dateStr = document.getElementById('tanggal_lahir').value;
    const display = document.getElementById('umur_display');

    if (!display) return;
    
    if (!dateStr) {
        display.textContent = 'Umur: -';
        return;
    }

    const tgl = new Date(dateStr);
    const now = new Date();

    if (tgl > now) {
        display.textContent = 'Umur: Tanggal Tidak Valid';
        return;
    }

    let y = now.getFullYear() - tgl.getFullYear();
    let m = now.getMonth() - tgl.getMonth();
    let d = now.getDate() - tgl.getDate();

    if (d < 0) {
        m--;
        d += new Date(now.getFullYear(), now.getMonth(), 0).getDate();
    }
    if (m < 0) {
        y--;
        m += 12;
    }

    display.textContent = `Umur: ${y}y ${m}m ${d}d`;
}

// Reset handler untuk membersihkan semua field
document.getElementById('btnReset').addEventListener('click', function () {
    // Reset semua field
    document.getElementById('poliklinikForm').reset();
    
    // Reset field readonly
    ['patient_id', 'display_no_rm', 'nama_pasien', 'tanggal_lahir', 'jenis_kelamin', 'golongan_darah'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = '';
    });
    
    // Reset display umur
    document.getElementById('umur_display').textContent = 'Umur: -';
    
    // Reset input pencarian
    document.getElementById('no_rm').value = '';
});

// Handler pemilihan kunjungan -> isi otomatis identitas/form
document.addEventListener('click', function (e) {
    if (e.target && e.target.classList.contains('btn-select-kunjungan')) {
        const btn = e.target;
        const pid = btn.getAttribute('data-patient-id');
        const noRm = btn.getAttribute('data-no-rm');
        const nama = btn.getAttribute('data-nama');
        const tgl = btn.getAttribute('data-tanggal-lahir');
        const jk = btn.getAttribute('data-jenis-kelamin');
        const gd = btn.getAttribute('data-golongan-darah');

        if (document.getElementById('patient_id')) document.getElementById('patient_id').value = pid || '';
        if (document.getElementById('form_no_rm')) document.getElementById('form_no_rm').value = noRm || '';
        if (document.getElementById('display_no_rm')) document.getElementById('display_no_rm').value = noRm || '';
        if (document.getElementById('nama_pasien')) document.getElementById('nama_pasien').value = nama || '';
        if (document.getElementById('tanggal_lahir')) document.getElementById('tanggal_lahir').value = tgl || '';
        if (document.getElementById('jenis_kelamin')) document.getElementById('jenis_kelamin').value = jk || '';
        if (document.getElementById('golongan_darah')) document.getElementById('golongan_darah').value = gd || '';

        // Hitung umur setelah set tanggal
        hitungUmur();
        // Scroll to form
        const formEl = document.getElementById('poliklinikForm');
        if (formEl) formEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});
</script>
@endpush