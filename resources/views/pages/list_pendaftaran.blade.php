
@extends('layout.app')

@section('content')
<div class="container"> 
    <div class="page-inner">

        <div class="page-header">
            <h3 class="fw-bold mb-3">List Pendaftaran Pasien</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="#">
                        <i class="fas fa-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="fas fa-angle-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Pendaftaran</a>
                </li>
            </ul>
        </div>

        <div class="card p-3">
            <div class="table-responsive">
                <table id="basic-datatables" class="display table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No RM</th>
                            <th>Nama Pasien</th>
                            <th>Poli Tujuan</th>
                            <th>Dokter</th>
                            <th>Tanggal Kunjungan</th>
                            <th>Jenis Pembayaran</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kunjungans as $k)
                        <tr>
                            <td>{{ $k->patient->no_rm ?? '-' }}</td>
                            <td>{{ $k->patient->nama_pasien ?? '-' }}</td>
                            <td>{{ $k->poli_tujuan }}</td>
                            <td>{{ $k->dokter->nama_dokter ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($k->tanggal_kunjungan)->format('d-m-Y') }}</td>
                            <td>{{ $k->jenis_bayar }}<br>
                                No Asuransi :
                                @if($k->jenis_bayar != 'Umum')
                                    {{ $k->no_asuransi ?? '-' }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <div class="form-button-action">
                                    <a
                                        href="{{ route('kunjungan.edit', $k->id) }}"
                                        class="btn btn-link btn-primary btn-sm"
                                        data-toggle="tooltip"
                                        title="Edit Data">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form
                                        action="{{ route('kunjungan.destroy', $k->id) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Hapus data ini?')">
                                        @csrf @method('DELETE')
                                        <button
                                            type="submit"
                                            class="btn btn-link btn-danger btn-sm"
                                            data-toggle="tooltip"
                                            title="Hapus Data">
                                            <i class="fas fa-trash"></i>
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

    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    // 1. Definisikan template DOM baru
    let table = $('#basic-datatables').DataTable({
        // Urutan: l (length/show entries), <date-filter>, f (filter/search)
        dom: 
            "<'row'<'col-md-3'l><'col-md-6'<'date-filter'>><'col-md-3'f>>" +
            "<'row'<'col-md-12'tr>>" +
            "<'row'<'col-md-5'i><'col-md-7'p>>",
        // Hapus konfigurasi dom yang sebelumnya agar lebih rapi.
    });

    // 2. Sisipkan HTML filter tanggal ke dalam div.date-filter
    $("div.date-filter").html(
        `
        <div class="d-flex justify-content-center align-items-center gap-2">
            <label class="fw-bold mb-0 me-1">Tanggal</label>
            <input type="date" id="minDate" class="form-control form-control-sm" style="width:150px">
            <span class="fw-bold">s/d</span>
            <input type="date" id="maxDate" class="form-control form-control-sm" style="width:150px">

            <button class="btn btn-primary btn-sm" id="filterDateBtn">Cari</button>
            <button class="btn btn-secondary btn-sm" id="resetDateBtn">Reset</button>
        </div>
        `
    );

    // Pastikan fungsi pencarian tetap berjalan dengan baik
    $.fn.dataTable.ext.search.push(function (settings, data) {
        let min = $('#minDate').val();
        let max = $('#maxDate').val();
        // data[3] adalah indeks kolom 'Tanggal Kunjungan'
        let date = data[3];

        if (!min && !max) 
            return true;
        
        let d = new Date(date);
        let minDate = min ? new Date(min) : null;
        let maxDate = max ? new Date(max) : null;
        
        // Atur agar tanggal perbandingan mencakup seluruh hari
        if(maxDate) {
            maxDate.setDate(maxDate.getDate() + 1); // Tambahkan 1 hari untuk perbandingan 'lebih kecil dari'
        }

        return !((minDate && d < minDate) || (maxDate && d >= maxDate));
    });

    $('#filterDateBtn').click(() => table.draw());

    $('#resetDateBtn').click(function () {
        $('#minDate').val('');
        $('#maxDate').val('');
        table.draw();
    });
});
</script>
@endpush