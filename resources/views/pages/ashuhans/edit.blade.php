@extends('layout.app')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">Edit Asuhan Medis</h2>
            </div>
            <div class="col-auto">
                <a href="{{ route('ashuhans.show', $asuhan) }}" class="btn btn-ghost-primary">Kembali</a>
            </div>
        </div>
    </div>
</div>

<div class="page-wrapper">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <form method="POST" action="{{ route('ashuhans.update', $asuhan) }}" class="card">
                    @csrf
                    @method('PUT')

                    <!-- Patient Info (Read Only) -->
                    <div class="card-header">
                        <h3 class="card-title">Informasi Pasien</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No RM</label>
                                <input type="text" class="form-control" value="{{ $asuhan->patient->no_rm }}" disabled>
                                <input type="hidden" name="no_rm" value="{{ $asuhan->patient->no_rm }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Pasien</label>
                                <input type="text" class="form-control" value="{{ $asuhan->patient->nama_pasien }}" disabled>
                            </div>
                        </div>
                    </div>

                    <!-- Asuhan Header -->
                    <div class="card-header">
                        <h3 class="card-title">Informasi Asuhan</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Poliklinik</label>
                                <select class="form-control @error('poli_tujuan') is-invalid @enderror" name="poli_tujuan" required>
                                    <option value="">Pilih Poliklinik</option>
                                    <option value="ugd" @selected($asuhan->poli_tujuan === 'ugd')>UGD</option>
                                    <option value="umum" @selected($asuhan->poli_tujuan === 'umum')>Umum</option>
                                    <option value="rawat_inap" @selected($asuhan->poli_tujuan === 'rawat_inap')>Rawat Inap</option>
                                </select>
                                @error('poli_tujuan')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Asuhan</label>
                                <input type="date" class="form-control @error('tanggal_asuhan') is-invalid @enderror" name="tanggal_asuhan" value="{{ $asuhan->tanggal_asuhan->toDateString() }}" required>
                                @error('tanggal_asuhan')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                                    <option value="draft" @selected($asuhan->status === 'draft')>Draft</option>
                                    <option value="final" @selected($asuhan->status === 'final')>Final</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Keluhan & Riwayat -->
                    <div class="card-header">
                        <h3 class="card-title">Keluhan & Riwayat</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Keluhan Utama</label>
                            <input type="text" class="form-control @error('keluhan_utama') is-invalid @enderror" name="keluhan_utama" value="{{ old('keluhan_utama', $asuhan->keluhan_utama) }}" required>
                            @error('keluhan_utama')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Riwayat Penyakit</label>
                            <textarea class="form-control @error('riwayat_penyakit') is-invalid @enderror" name="riwayat_penyakit" rows="2">{{ old('riwayat_penyakit', $asuhan->riwayat_penyakit) }}</textarea>
                            @error('riwayat_penyakit')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Riwayat Alergi</label>
                            <textarea class="form-control @error('riwayat_alergi') is-invalid @enderror" name="riwayat_alergi" rows="2">{{ old('riwayat_alergi', $asuhan->riwayat_alergi) }}</textarea>
                            @error('riwayat_alergi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Diagnosa & Tindakan -->
                    <div class="card-header">
                        <h3 class="card-title">Diagnosa & Tindakan</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Diagnosa Medis</label>
                            <input type="text" class="form-control @error('diagnosa_medis') is-invalid @enderror" name="diagnosa_medis" value="{{ old('diagnosa_medis', $asuhan->diagnosa_medis) }}" required>
                            @error('diagnosa_medis')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tindakan / Terapi</label>
                            <textarea class="form-control @error('tindakan_terapi') is-invalid @enderror" name="tindakan_terapi" rows="2">{{ old('tindakan_terapi', $asuhan->tindakan_terapi) }}</textarea>
                            @error('tindakan_terapi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Resep / Obat</label>

                            {{-- Hidden textarea to store serialized resep JSON --}}
                            <textarea class="form-control d-none @error('resep') is-invalid @enderror" id="resep" name="resep" rows="3">{{ old('resep', $asuhan->resep) }}</textarea>
                            @error('resep')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                            {{-- Visible medicine selector UI --}}
                            <div id="medicineSelector" class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong>Daftar Obat</strong>
                                    <button type="button" class="btn btn-sm btn-primary" id="btnAddMedicine">Tambah Obat</button>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-sm" id="medicinesTable">
                                        <thead>
                                            <tr>
                                                <th>Obat</th>
                                                <th>Unit</th>
                                                <th>Harga</th>
                                                <th>Jumlah</th>
                                                <th>Catatan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- rows added dynamically -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <small class="text-muted">Daftar obat akan disimpan saat menyimpan asuhan.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan Perawatan</label>
                            <textarea class="form-control @error('catatan_perawatan') is-invalid @enderror" name="catatan_perawatan" rows="2">{{ old('catatan_perawatan', $asuhan->catatan_perawatan) }}</textarea>
                            @error('catatan_perawatan')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Vital Signs -->
                    <div class="card-header">
                        <h3 class="card-title">Tanda-Tanda Vital</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tekanan Darah (mm/Hg)</label>
                                <input type="text" class="form-control @error('tekanan_darah') is-invalid @enderror" name="tekanan_darah" value="{{ old('tekanan_darah', $asuhan->tekanan_darah) }}" placeholder="120/80">
                                @error('tekanan_darah')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nadi (X/Menit)</label>
                                <input type="number" class="form-control @error('nadi') is-invalid @enderror" name="nadi" value="{{ old('nadi', $asuhan->nadi) }}" placeholder="80">
                                @error('nadi')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Suhu (°C)</label>
                                <input type="number" step="0.1" class="form-control @error('suhu') is-invalid @enderror" name="suhu" value="{{ old('suhu', $asuhan->suhu) }}" placeholder="36.5">
                                @error('suhu')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Respirasi (X/Menit)</label>
                                <input type="number" class="form-control @error('respirasi') is-invalid @enderror" name="respirasi" value="{{ old('respirasi', $asuhan->respirasi) }}" placeholder="20">
                                @error('respirasi')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="card-footer text-end">
                        <a href="{{ route('ashuhans.show', $asuhan) }}" class="btn btn-link">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@php
    $existingPrescriptions = [];
    if (isset($asuhan->prescriptionItems) && $asuhan->prescriptionItems->isNotEmpty()) {
        $existingPrescriptions = $asuhan->prescriptionItems->map(function($i){
            return [
                'id' => $i->medicine_id,
                'name' => $i->name,
                'unit' => $i->unit,
                'price' => $i->price,
                'qty' => $i->qty,
                'note' => $i->note,
            ];
        })->toArray();
    } else {
        $existingPrescriptions = $asuhan->resep ? json_decode($asuhan->resep, true) : [];
    }
@endphp

@push('scripts')
<script>
const medicines = @json($medicines ?? []);
const existingPrescriptions = @json($existingPrescriptions);

function buildMedicineOptions() {
    if (!medicines || medicines.length === 0) return '<option value="">-- Tidak ada data obat --</option>';
    return medicines.map(m => `<option value="${m.id}" data-unit="${m.unit}" data-price="${m.price}" data-name="${m.name}">${m.name} — ${m.category}</option>`).join('');
}

function addMedicineRow(prefill = null) {
    const tbody = document.querySelector('#medicinesTable tbody');
    if (!tbody) return;

    const tr = document.createElement('tr');
    const optionsHtml = buildMedicineOptions();

    tr.innerHTML = `
        <td>
            <select class="form-control form-control-sm medicine-select">
                <option value="">-- Pilih Obat --</option>
                ${optionsHtml}
            </select>
        </td>
        <td><input type="text" class="form-control form-control-sm medicine-unit" readonly></td>
        <td><input type="text" class="form-control form-control-sm medicine-price" readonly></td>
        <td><input type="number" min="1" value="1" class="form-control form-control-sm medicine-qty"></td>
        <td><input type="text" class="form-control form-control-sm medicine-note"></td>
        <td><button type="button" class="btn btn-sm btn-danger btn-remove-medicine">Hapus</button></td>
    `;

    tbody.appendChild(tr);

    if (prefill) {
        const sel = tr.querySelector('.medicine-select');
        if (sel) sel.value = prefill.id;
        const qty = tr.querySelector('.medicine-qty'); if (qty) qty.value = prefill.qty || prefill.qty === 0 ? prefill.qty : 1;
        const note = tr.querySelector('.medicine-note'); if (note) note.value = prefill.note || prefill.catatan || '';
        // trigger change to fill unit/price
        const evt = new Event('change');
        tr.querySelector('.medicine-select').dispatchEvent(evt);
    }
}

document.getElementById('btnAddMedicine').addEventListener('click', function () {
    if (!medicines || medicines.length === 0) {
        alert('Belum ada data obat. Silakan tambah data obat di menu Kelola Obat terlebih dahulu.');
        return;
    }
    addMedicineRow();
});

document.querySelector('#medicinesTable tbody').addEventListener('click', function (e) {
    if (e.target && e.target.classList.contains('btn-remove-medicine')) {
        const tr = e.target.closest('tr');
        if (tr) tr.remove();
    }
});

document.querySelector('#medicinesTable tbody').addEventListener('change', function (e) {
    if (e.target && e.target.classList.contains('medicine-select')) {
        const sel = e.target;
        const tr = sel.closest('tr');
        const opt = sel.options[sel.selectedIndex];
        const unitEl = tr.querySelector('.medicine-unit');
        const priceEl = tr.querySelector('.medicine-price');

        if (opt && opt.value) {
            unitEl.value = opt.getAttribute('data-unit') || '';
            priceEl.value = opt.getAttribute('data-price') || '';
        } else {
            unitEl.value = '';
            priceEl.value = '';
        }
    }
});

// On form submit, serialize medicines into resep textarea as JSON
document.querySelector('form.card').addEventListener('submit', function (e) {
    const rows = Array.from(document.querySelectorAll('#medicinesTable tbody tr'));
    const payload = [];

    rows.forEach(tr => {
        const sel = tr.querySelector('.medicine-select');
        if (!sel) return;
        const medId = sel.value;
        if (!medId) return;
        const opt = sel.options[sel.selectedIndex];
        const name = opt ? (opt.getAttribute('data-name') || opt.text) : '';
        const unit = tr.querySelector('.medicine-unit')?.value || '';
        const price = tr.querySelector('.medicine-price')?.value || '';
        const qty = tr.querySelector('.medicine-qty')?.value || 1;
        const note = tr.querySelector('.medicine-note')?.value || '';

        payload.push({ id: medId, name, unit, price, qty, note });
    });

    const hidden = document.getElementById('resep');
    if (hidden) hidden.value = JSON.stringify(payload);
    // allow submit
});

// Prefill existing prescriptions if any
if (Array.isArray(existingPrescriptions) && existingPrescriptions.length > 0) {
    existingPrescriptions.forEach(item => addMedicineRow(item));
}

// Disable add button when no medicines
if (!medicines || medicines.length === 0) {
    document.getElementById('btnAddMedicine').classList.add('disabled');
}
</script>
@endpush
