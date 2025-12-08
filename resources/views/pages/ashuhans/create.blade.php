@extends('layout.app')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">Buat Asuhan Medis Baru</h2>
            </div>
            <div class="col-auto">
                <a href="{{ route('pages.dashboard') }}" class="btn btn-ghost-primary">Kembali</a>
            </div>
        </div>
    </div>
</div>

<div class="page-wrapper">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Form Asuhan Medis</h3>
                    </div>
                    <form method="POST" action="{{ route('ashuhans.store') }}" class="card">
                        @csrf

                        <!-- Patient Search -->
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">Cari Pasien</h5>
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label class="form-label">No RM atau Nama Pasien</label>
                                    <input type="text" class="form-control @error('no_rm') is-invalid @enderror" id="no_rm" placeholder="Masukkan No RM atau nama pasien">
                                    @error('no_rm')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" class="btn btn-primary w-100" id="btnSearchRm">Cari Pasien</button>
                                </div>
                            </div>

                            <input type="hidden" name="no_rm" id="form_no_rm">
                            <input type="hidden" name="patient_id" id="patient_id">

                            <!-- Selected Patient Info -->
                            <div id="patientInfo" style="display: none;">
                                <div class="border p-3 rounded mb-4">
                                    <h5 class="fw-bold mb-3">Data Pasien Terpilih</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">No RM</label>
                                            <input type="text" class="form-control" id="display_no_rm" disabled>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Nama Pasien</label>
                                            <input type="text" class="form-control" id="nama_pasien" disabled>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Tanggal Lahir</label>
                                            <input type="text" class="form-control" id="tanggal_lahir" disabled>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Jenis Kelamin</label>
                                            <input type="text" class="form-control" id="jenis_kelamin" disabled>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Golongan Darah</label>
                                            <input type="text" class="form-control" id="golongan_darah" disabled>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <p id="umur_display" class="text-muted">Umur: -</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Asuhan Details -->
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">Informasi Asuhan</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Poliklinik</label>
                                    <select class="form-control @error('poli_tujuan') is-invalid @enderror" name="poli_tujuan" required>
                                        <option value="">Pilih Poliklinik</option>
                                        <option value="ugd" @selected(old('poli_tujuan') === 'ugd')>UGD</option>
                                        <option value="umum" @selected(old('poli_tujuan') === 'umum')>Umum</option>
                                        <option value="rawat_inap" @selected(old('poli_tujuan') === 'rawat_inap')>Rawat Inap</option>
                                    </select>
                                    @error('poli_tujuan')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal Asuhan</label>
                                    <input type="date" class="form-control @error('tanggal_asuhan') is-invalid @enderror" name="tanggal_asuhan" value="{{ old('tanggal_asuhan', now()->toDateString()) }}" required>
                                    @error('tanggal_asuhan')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                                        <option value="draft" @selected(old('status') === 'draft')>Draft</option>
                                        <option value="final" @selected(old('status') === 'final')>Final</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Keluhan & Riwayat -->
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">Keluhan & Riwayat</h5>
                            <div class="mb-3">
                                <label class="form-label">Keluhan Utama</label>
                                <input type="text" class="form-control @error('keluhan_utama') is-invalid @enderror" name="keluhan_utama" value="{{ old('keluhan_utama') }}" required>
                                @error('keluhan_utama')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Riwayat Penyakit</label>
                                <textarea class="form-control @error('riwayat_penyakit') is-invalid @enderror" name="riwayat_penyakit" rows="2">{{ old('riwayat_penyakit') }}</textarea>
                                @error('riwayat_penyakit')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Riwayat Alergi</label>
                                <textarea class="form-control @error('riwayat_alergi') is-invalid @enderror" name="riwayat_alergi" rows="2">{{ old('riwayat_alergi') }}</textarea>
                                @error('riwayat_alergi')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Diagnosa & Tindakan -->
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">Diagnosa & Tindakan</h5>
                            <div class="mb-3">
                                <label class="form-label">Diagnosa Medis</label>
                                <input type="text" class="form-control @error('diagnosa_medis') is-invalid @enderror" name="diagnosa_medis" value="{{ old('diagnosa_medis') }}" required>
                                @error('diagnosa_medis')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                                <div class="mb-3">
                                    <label class="form-label">Tindakan / Terapi</label>
                                    <textarea class="form-control @error('tindakan_terapi') is-invalid @enderror" name="tindakan_terapi" rows="2">{{ old('tindakan_terapi') }}</textarea>
                                    @error('tindakan_terapi')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label mt-3">Resep / Obat</label>
                                    <textarea class="form-control @error('resep') is-invalid @enderror" name="resep" rows="3">{{ old('resep') }}</textarea>
                                    @error('resep')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Catatan Perawatan</label>
                                <textarea class="form-control @error('catatan_perawatan') is-invalid @enderror" name="catatan_perawatan" rows="2">{{ old('catatan_perawatan') }}</textarea>
                                @error('catatan_perawatan')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Vital Signs -->
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">Tanda-Tanda Vital</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tekanan Darah (mm/Hg)</label>
                                    <input type="text" class="form-control @error('tekanan_darah') is-invalid @enderror" name="tekanan_darah" value="{{ old('tekanan_darah') }}" placeholder="120/80">
                                    @error('tekanan_darah')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nadi (X/Menit)</label>
                                    <input type="number" class="form-control @error('nadi') is-invalid @enderror" name="nadi" value="{{ old('nadi') }}" placeholder="80">
                                    @error('nadi')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Suhu (°C)</label>
                                    <input type="number" step="0.1" class="form-control @error('suhu') is-invalid @enderror" name="suhu" value="{{ old('suhu') }}" placeholder="36.5">
                                    @error('suhu')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Respirasi (X/Menit)</label>
                                    <input type="number" class="form-control @error('respirasi') is-invalid @enderror" name="respirasi" value="{{ old('respirasi') }}" placeholder="20">
                                    @error('respirasi')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="card-footer text-end">
                            <a href="{{ route('pages.dashboard') }}" class="btn btn-link">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Asuhan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Fungsi untuk mencari pasien berdasarkan No RM atau Nama
document.getElementById('btnSearchRm').addEventListener('click', async function () {
    const no_rm = document.getElementById('no_rm').value.trim();
    
    if (!no_rm) {
        alert('Masukkan No RM atau nama pasien terlebih dahulu');
        return;
    }

    try {
        const res = await fetch(`/pasien/cari?q=${encodeURIComponent(no_rm)}`);
        
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
        document.getElementById('form_no_rm').value = p.no_rm || no_rm;
        document.getElementById('display_no_rm').value = p.no_rm || no_rm;
        document.getElementById('nama_pasien').value = p.nama_pasien || '';
        document.getElementById('tanggal_lahir').value = p.tanggal_lahir || '';
        document.getElementById('jenis_kelamin').value = p.jenis_kelamin || '';
        document.getElementById('golongan_darah').value = p.golongan_darah || '';
        
        // Tampilkan patient info
        document.getElementById('patientInfo').style.display = 'block';
        
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
</script>
@endpush
@endsection
