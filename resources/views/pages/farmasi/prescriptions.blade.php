@extends('layout.app')

@section('content')
<div class="container">
    <div class="page-inner">
        <h3 class="fw-bold mb-3">Daftar Resep (Farmasi)</h3>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Terjadi kesalahan validasi:</strong>
                <ul class="mb-0">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="mb-3 d-flex">
            <form method="GET" action="{{ route('farmasi.prescriptions') }}" class="d-flex flex-grow-1 me-2">
                <input type="text" name="q" value="{{ old('q', $q ?? '') }}" class="form-control me-2" placeholder="Cari No RM atau Nama pasien">
                <button class="btn btn-secondary">Cari</button>
            </form>

            @if(isset($prescriptions) && $prescriptions->isNotEmpty())
                <form method="POST" action="{{ route('farmasi.prescriptions.finish_all') }}" style="display:inline-block">
                    @csrf
                    @foreach($prescriptions as $item)
                        <input type="hidden" name="ids[]" value="{{ $item->id }}">
                    @endforeach
                    <button class="btn btn-sm btn-warning">Finish Semua Antrian</button>
                </form>
            @endif
        </div>
        @if($prescriptions->isEmpty())
            <div class="alert alert-info">Tidak ada resep yang menunggu diambil.</div>
        @else
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>No RM</th>
                        <th>Nama</th>
                        <th>Poli</th>
                        <th>Tanggal</th>
                        <th>Detail Obat</th>
                        <th>Total Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($prescriptions as $p)
                        <tr>
                            <td>{{ $p->patient->no_rm ?? '-' }}</td>
                            <td>{{ $p->patient->nama_pasien ?? '-' }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $p->poli_tujuan)) }}</td>
                            <td>{{ optional($p->tanggal_asuhan)->format('d-m-Y') }}</td>
                            <td>
                                @if(isset($p->prescriptionItems) && $p->prescriptionItems->isNotEmpty())
                                    <ul class="mb-0">
                                        @foreach($p->prescriptionItems as $it)
                                            <li>{{ $it->name }} x{{ $it->qty }} @if(isset($it->price) && $it->price) - Rp {{ number_format($it->price,0,',','.') }} @endif</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $total = null;
                                    if (!empty($p->resep_total)) {
                                        $total = $p->resep_total;
                                    } elseif (isset($p->prescriptionItems) && $p->prescriptionItems->isNotEmpty()) {
                                        $sum = 0;
                                        foreach ($p->prescriptionItems as $it) {
                                            $price = isset($it->price) ? floatval($it->price) : 0;
                                            $qty = isset($it->qty) ? intval($it->qty) : 0;
                                            $sum += ($price * $qty);
                                        }
                                        $total = $sum;
                                    }
                                @endphp
                                @if($total !== null)
                                    Rp {{ number_format($total,0,',','.') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary btn-open-dispense" data-id="{{ $p->id }}">Input Obat</button>
                                
                                <form method="POST" action="{{ route('farmasi.prescriptions.collect', $p) }}" style="display:inline-block">
                                    @csrf
                                    <button class="btn btn-sm btn-success">Tandai Diambil</button>
                                </form>

                                @php
                                    $existingItems = [];
                                    if (isset($p->prescriptionItems) && $p->prescriptionItems->isNotEmpty()) {
                                        $existingItems = $p->prescriptionItems->map(function($it){
                                            return [
                                                'id' => $it->medicine_id,
                                                'name' => $it->name,
                                                'unit' => $it->unit,
                                                'price' => $it->price,
                                                'qty' => $it->qty,
                                                'note' => $it->note,
                                            ];
                                        })->toArray();
                                    }
                                @endphp

                                <div class="mt-2 dispense-form" id="dispense-form-{{ $p->id }}" data-existing='@json($existingItems)' style="display:none;">
                                    <form method="POST" action="{{ route('farmasi.prescriptions.dispense', $p) }}">
                                        @csrf
                                        <table class="table table-sm mb-2">
                                            <thead>
                                                <tr>
                                                    <th>Obat</th>
                                                    <th>Unit</th>
                                                    <th>Harga</th>
                                                    <th>Jumlah</th>
                                                    <th>Catatan</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody class="dispense-rows" data-form-id="{{ $p->id }}">
                                                {{-- rows added by JS --}}
                                            </tbody>
                                        </table>

                                        <div class="mb-2 d-flex gap-2 align-items-center">
                                            <button type="button" class="btn btn-sm btn-outline-secondary btn-add-dispense" data-id="{{ $p->id }}">Tambah Baris</button>
                                            <div class="ms-auto">
                                                <strong>Total: Rp <span class="dispense-total-display">0</span></strong>
                                            </div>
                                            <input type="hidden" name="total" class="dispense-total-input" value="0">
                                        </div>

                                        <div class="text-end">
                                            <button type="submit" class="btn btn-sm btn-primary">Simpan & Selesai</button>
                                        </div>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                {{ $prescriptions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
const medicines = @json($medicines ?? []);
let rowCounters = {}; // Track row indices per form

function buildOptions() {
    if (!medicines || medicines.length === 0) return '<option value="">-- Tidak ada obat --</option>';
    let opts = '<option value="">-- Pilih Obat --</option>';
    medicines.forEach(m => {
        opts += `<option value="${m.id}" data-unit="${m.unit || ''}" data-price="${m.price || 0}" data-name="${m.name || ''}">${m.name}${m.category ? ' — ' + m.category : ''}</option>`;
    });
    return opts;
}

function createDispenseRow(formId, index, existingData = null) {
    const tr = document.createElement('tr');
    tr.dataset.rowIndex = index;
    
    const idVal = existingData?.id || '';
    const nameVal = existingData?.name || '';
    const unitVal = existingData?.unit || '';
    const priceVal = existingData?.price || 0;
    const qtyVal = existingData?.qty || 1;
    const noteVal = existingData?.note || '';
    
    tr.innerHTML = `
        <td>
            <select name="items[${index}][id]" class="form-control form-control-sm dispense-select">
                ${buildOptions()}
            </select>
            <input type="hidden" name="items[${index}][name]" class="dispense-name" value="${nameVal}">
        </td>
        <td>
            <input type="text" name="items[${index}][unit]" class="form-control form-control-sm dispense-unit" value="${unitVal}" readonly>
        </td>
        <td>
            <input type="number" step="0.01" name="items[${index}][price]" class="form-control form-control-sm dispense-price" value="${priceVal}">
        </td>
        <td>
            <input type="number" name="items[${index}][qty]" class="form-control form-control-sm dispense-qty" value="${qtyVal}" min="1" required>
        </td>
        <td>
            <input type="text" name="items[${index}][note]" class="form-control form-control-sm dispense-note" value="${noteVal}">
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-danger btn-remove-row">Hapus</button>
        </td>
    `;
    
    // Set selected medicine if exists
    if (idVal) {
        const select = tr.querySelector('.dispense-select');
        if (select) select.value = idVal;
    }
    
    return tr;
}

document.querySelectorAll('.btn-open-dispense').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const container = document.getElementById('dispense-form-' + id);
        if (!container) return;
        
        const isVisible = container.style.display !== 'none';
        container.style.display = isVisible ? 'none' : 'block';

        const tbody = container.querySelector('.dispense-rows');
        if (!isVisible && tbody && tbody.children.length === 0) {
            // Initialize row counter for this form
            rowCounters[id] = 0;
            
            try {
                const existing = JSON.parse(container.getAttribute('data-existing') || '[]');
                if (Array.isArray(existing) && existing.length > 0) {
                    existing.forEach(item => {
                        const row = createDispenseRow(id, rowCounters[id], item);
                        tbody.appendChild(row);
                        rowCounters[id]++;
                    });
                } else {
                    // Add one empty row
                    const row = createDispenseRow(id, rowCounters[id]);
                    tbody.appendChild(row);
                    rowCounters[id]++;
                }
                recalcTotal(tbody);
            } catch (err) {
                console.error('Error loading existing items:', err);
                const row = createDispenseRow(id, rowCounters[id]);
                tbody.appendChild(row);
                rowCounters[id]++;
            }
        }
    });
});

document.querySelectorAll('.btn-add-dispense').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const container = document.getElementById('dispense-form-' + id);
        if (!container) return;
        
        const tbody = container.querySelector('.dispense-rows');
        if (!tbody) return;
        
        // Initialize counter if not exists
        if (!rowCounters[id]) rowCounters[id] = tbody.children.length;
        
        const row = createDispenseRow(id, rowCounters[id]);
        tbody.appendChild(row);
        rowCounters[id]++;
        
        recalcTotal(tbody);
    });
});

document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('btn-remove-row')) {
        const tr = e.target.closest('tr');
        if (!tr) return;
        
        const tbody = tr.closest('tbody');
        tr.remove();
        
        if (tbody) recalcTotal(tbody);
    }
});

document.addEventListener('change', function(e) {
    if (e.target && e.target.classList.contains('dispense-select')) {
        const sel = e.target;
        const tr = sel.closest('tr');
        if (!tr) return;
        
        const opt = sel.options[sel.selectedIndex];
        const nameInput = tr.querySelector('.dispense-name');
        const unit = tr.querySelector('.dispense-unit');
        const price = tr.querySelector('.dispense-price');
        
        if (opt && opt.value) {
            if (nameInput) nameInput.value = opt.getAttribute('data-name') || '';
            if (unit) unit.value = opt.getAttribute('data-unit') || '';
            if (price) price.value = parseFloat(opt.getAttribute('data-price')) || 0;
        } else {
            if (nameInput) nameInput.value = '';
            if (unit) unit.value = '';
            if (price) price.value = 0;
        }
        
        const tbody = tr.closest('tbody');
        if (tbody) recalcTotal(tbody);
    }
});

function recalcTotal(tbody) {
    if (!tbody) return;
    
    let total = 0;
    Array.from(tbody.querySelectorAll('tr')).forEach(tr => {
        const priceEl = tr.querySelector('.dispense-price');
        const qtyEl = tr.querySelector('.dispense-qty');
        const price = priceEl ? parseFloat(priceEl.value || 0) : 0;
        const qty = qtyEl ? parseInt(qtyEl.value || 0) : 0;
        
        if (!isNaN(price) && !isNaN(qty)) {
            total += price * qty;
        }
    });

    const form = tbody.closest('form');
    if (form) {
        const display = form.querySelector('.dispense-total-display');
        const hidden = form.querySelector('.dispense-total-input');
        
        if (display) display.textContent = total.toLocaleString('id-ID');
        if (hidden) hidden.value = total;
    }
}

document.addEventListener('input', function(e) {
    if (e.target && (e.target.classList.contains('dispense-price') || e.target.classList.contains('dispense-qty'))) {
        const tbody = e.target.closest('tbody');
        if (tbody) recalcTotal(tbody);
    }
});
</script>
@endpush