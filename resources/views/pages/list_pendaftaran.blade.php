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
                  <div class="card-header">
                    <h4 class="card-title">List Pendaftaran Pasien</h4>
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
                                  <button
                                    type="button"
                                    data-bs-toggle="tooltip"
                                    title=""
                                    class="btn btn-link btn-primary btn-lg"
                                    data-original-title="Edit Task"
                                  >
                                    <i class="fa fa-edit"></i>
                                  </button>
                                  <button
                                    type="button"
                                    data-bs-toggle="tooltip"
                                    title=""
                                    class="btn btn-link btn-danger"
                                    data-original-title="Remove"
                                  >
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