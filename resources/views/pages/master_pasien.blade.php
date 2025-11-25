@php
    \Carbon\Carbon::setLocale('id'); // Atur locale ke Indonesia
@endphp

@extends('layout.app')

@section('content')
<div class="container">
          <div class="page-inner">
            <div class="page-header">
              <h3 class="fw-bold mb-3">Pasien</h3>
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
                  <a href="#">Pasien</a>
                </li>
                <li class="separator">
                  <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                  <a href="#">Master Pasien</a>
                </li>
              </ul>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <h4 class="card-title">List Data Pasien</h4>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table
                        id="basic-datatables"
                        class="display table table-striped table-hover"
                      >
                        <thead>
                          <tr>
                            <th>No RM Pasien</th>
                            <th>Informasi Pasien</th>
                            <th>Tanggal Lahir / Umur</th>
                            <th>No KTP / BPJS</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($patients as $p)
                              <tr>

                                  {{-- Kolom 1: No RM Pasien --}}
                                  <td>{{ $p->no_rm ?? '-' }}</td>

                                  {{-- Kolom 2: Informasi Pasien --}}
                                  <td>
                                      <strong>{{ $p->nama_pasien ?? '-' }}</strong><br>
                                      Alamat: {{ $p->alamat ?? '-' }}<br>
                                      Nama Penanggung Jawab: {{ $p->kunjungans->last()->pj_nama ?? '-' }} 
                                  </td>

                                  {{-- Kolom 3: Tanggal Lahir / Umur --}}
                                  <td>
                                      {{-- Tanggal Lahir --}}
                                      {{ $p->tanggal_lahir 
                                          ? \Carbon\Carbon::parse($p->tanggal_lahir)->translatedFormat('d F Y')
                                          : '-' 
                                      }}
                                      <br>

                                      {{-- Umur --}}
                                      Umur: 
                                      {{ $p->tanggal_lahir 
                                          ? \Carbon\Carbon::parse($p->tanggal_lahir)->age . ' Tahun'
                                          : '-'
                                      }}
                                  </td>

                                  {{-- Kolom 4: No KTP / BPJS --}}
                                  <td>
                                      <strong>NO KTP</strong><br>
                                      {{ $p->no_ktp ?? '-' }}<br>

                                      <strong>NO BPJS</strong><br>
                                      {{ $p->kunjungans->last()->no_asuransi ?? '-' }}
                                  </td>

                                  {{-- Kolom 5: Action --}}
                                  <td>
                                      <div class="form-button-action">
                                          <button type="button" class="btn btn-link btn-primary btn-lg" 
                                                  title="Edit Data Pasien">
                                              <i class="fa fa-edit"></i>
                                          </button>

                                          <button type="button" class="btn btn-link btn-danger" 
                                                  title="Hapus Data Pasien">
                                              <i class="fa fa-times"></i>
                                          </button>
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