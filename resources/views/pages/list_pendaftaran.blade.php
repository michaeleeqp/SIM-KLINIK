@php
    \Carbon\Carbon::setLocale('id'); // Atur locale ke Indonesia
@endphp

@extends('layout.app')

@section('content')
<div class="container">
          <div class="page-inner">
            <div class="page-header">
              <h3 class="fw-bold mb-3">List Pendaftaran</h3>
              <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                  <a href="/dashboard">
                    <i class="icon-home"></i>
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
                  <a href="#">List Pendaftaran</a>
                </li>
              </ul>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header d-flex align-items-center justify-content-between">
                    <h4 class="card-title">List Pendaftaran Pasien</h4>
                    <div>
                      <a href="{{ route('list.pendaftaran') }}" class="btn btn-sm btn-outline-secondary">Semua</a>
                      <a href="{{ route('list.pendaftaran') }}?tujuan=ugd" class="btn btn-sm btn-outline-primary">UGD</a>
                      <a href="{{ route('list.pendaftaran') }}?tujuan=umum" class="btn btn-sm btn-outline-info">Poliklinik Umum</a>
                    </div>
                  </div>
                  <div class="card-body">
                    <form method="GET" class="mb-3">
                      <div class="row g-2 align-items-center">
                        <div class="col-auto">
                          <input type="date" name="date" class="form-control" value="{{ $date ?? '' }}">
                        </div>
                      
                        <div class="col-auto">
                          <button class="btn btn-sm btn-primary">Filter</button>
                          <a href="{{ route('list.pendaftaran') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                        </div>
                      </div>
                    </form>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table
                        id="basic-datatables"
                        class="display table table-striped table-hover"
                      >
                        <thead>
                          <tr>
                            <th>No RM Pasien</th>
                            <th>Nama Pasien</th>
                            <th>Poli Tujuan</th>
                            <th>Tanggal Kunjungan</th>
                            <th>Jenis Pembayaran</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($kunjungans as $k)
                            <tr>
                              <td>{{ $k->patient->no_rm ?? '-' }}</td>
                              <td>
                                {{ $k->patient->nama_pasien ?? '-' }}<br>
                                Umur : 
                                @if(isset($k->patient->tanggal_lahir))
                                    {{ \Carbon\Carbon::parse($k->patient->tanggal_lahir)->age }}
                                @else
                                    -
                                @endif
                              </td>
                              <td>{{ $k->poli_tujuan ?? '-' }}</td> {{-- Tujuan poli --}}
                              <td>
                                {{ $k->tanggal_kunjungan 
                                    ? \Carbon\Carbon::parse($k->tanggal_kunjungan)->translatedFormat('d F Y') 
                                     : '-' 
                                  }}
                              </td>
                              <td>{{ $k->jenis_bayar ?? '-' }}</td> {{-- Jenis pembayaran --}}
                              <td>
                                <div class="form-button-action">
                                    <a href="{{ route('kunjungan.edit', $k->id) }}"
                                      class="btn btn-link btn-primary btn-lg" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>

                                    <form action="{{ route('kunjungan.destroy', $k->id) }}" 
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus data ini?')"
                                          style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-link btn-danger" title="Hapus">
                                            <i class="fa fa-trash"></i>
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
            </div>
          </div>
        </div>
@endsection

@push('scripts')
<script>
      $(document).ready(function () {
        $("#basic-datatables").DataTable({});

        $("#multi-filter-select").DataTable({
          pageLength: 5,
          initComplete: function () {
            this.api()
              .columns()
              .every(function () {
                var column = this;
                var select = $(
                  '<select class="form-select"><option value=""></option></select>'
                )
                  .appendTo($(column.footer()).empty())
                  .on("change", function () {
                    var val = $.fn.dataTable.util.escapeRegex($(this).val());

                    column
                      .search(val ? "^" + val + "$" : "", true, false)
                      .draw();
                  });

                column
                  .data()
                  .unique()
                  .sort()
                  .each(function (d, j) {
                    select.append(
                      '<option value="' + d + '">' + d + "</option>"
                    );
                  });
              });
          },
        });

        // Add Row
        $("#add-row").DataTable({
          pageLength: 5,
        });

        var action =
          '<td> <div class="form-button-action"> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

        $("#addRowButton").click(function () {
          $("#add-row")
            .dataTable()
            .fnAddData([
              $("#addName").val(),
              $("#addPosition").val(),
              $("#addOffice").val(),
              action,
            ]);
          $("#addRowModal").modal("hide");
        });
      });
</script>
@endpush