@php
// Cek apakah URL saat ini berada di bawah rute 'pendaftaran'
    $isPendaftaranActive = request()->is('pendaftaran/*') || request()->is('list/pendaftaran');
@endphp

<div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
          <!-- Logo Header -->
          <div class="logo-header" data-background-color="dark">
            <a href="/dashboard" class="logo">
              <img
                src="{{asset('template/assets/img/kaiadmin/logo.png')}}"
                alt="navbar brand"
                class="navbar-brand"
                height="40"
              />
            </a>
            <div class="nav-toggle">
              <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
              </button>
              <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
              </button>
            </div>
            <button class="topbar-toggler more">
              <i class="gg-more-vertical-alt"></i>
            </button>
          </div>
          <!-- End Logo Header -->
        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
          <div class="sidebar-content">
            <ul class="nav nav-secondary">
              <li class="nav-item @if(request()->is('dashboard')) active @endif">
                <a
                  href="/dashboard"
                >
                  <i class="fas fa-home"></i>
                  <p>Dashboard</p>
                </a>
              </li>
              <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">Menu</h4>
              </li>
              @if(auth()->check() && in_array(auth()->user()->role, ['perawat', 'dokter', 'admin']))
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#base">
                  <i class="fas fa-layer-group"></i>
                  <p>Pendaftaran</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="base">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="/pendaftaran/lama">
                        <span class="sub-item">Pasien Lama</span>
                      </a>
                    </li>
                    <li>
                      <a href="/pendaftaran/baru">
                        <span class="sub-item">Pasien Baru</span>
                      </a>
                    </li>
                    <li>
                      <a href="/list/pendaftaran">
                        <span class="sub-item">List Pendaftaran</span>
                      </a>
                    </li>         
                  </ul>
                </div>
              </li>

              <li class="nav-item @if($isPendaftaranActive) active @endif">
                <a data-bs-toggle="collapse" href="#sidebarLayouts">
                  <i class="fas fa-th-list"></i>
                  <p>Pasien</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="sidebarLayouts">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="/master/pasien">
                        <span class="sub-item">Master pasien</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>

              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#forms">
                  <i class="fas fa-pen-square"></i>
                  <p>Poliklinik</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="forms">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="/ugd">
                        <span class="sub-item">UGD</span>
                      </a>
                    </li>
                    <li>
                      <a href="/umum">
                        <span class="sub-item">Klinik Umum</span>
                      </a>
                    </li>
                    <li>
                      <a href="/ranap">
                        <span class="sub-item">Rawat Inap</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('ashuhans.index') }}">
                        <span class="sub-item">Asuhan Medis</span>
                      </a>
                    </li>
                  </ul>                  
                </div>
              </li>
            @endif
              @if(auth()->user() && in_array(auth()->user()->role, ['farmasi', 'admin']))
              <li class="nav-item @if(request()->is('farmasi*') || request()->is('medicines*')) active @endif">
                <a data-bs-toggle="collapse" href="#farmasiMenu">
                  <i class="fas fa-pills"></i>
                  <p>Farmasi</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="farmasiMenu">
                  <ul class="nav nav-collapse">
                    <li class="@if(request()->routeIs('farmasi.prescriptions')) active @endif">
                      <a href="{{ route('farmasi.prescriptions') }}">
                        <span class="sub-item">Resep</span>
                      </a>
                    </li>
                    <li class="@if(request()->routeIs('medicines.*')) active @endif">
                      <a href="{{ route('medicines.index') }}">
                        <span class="sub-item">Kelola Obat</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              @endif
              @if(auth()->user() && in_array(auth()->user()->role, ['rekam_medis', 'admin']))
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#rekamMedis">
                  <i class="fas fa-file-medical"></i>
                  <p>Rekam Medis</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="rekamMedis">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="/master/pasien">
                        <span class="sub-item">Master Pasien</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('ashuhans.index') }}">
                        <span class="sub-item">Data Medis</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              @endif
              @if(auth()->user() && in_array(auth()->user()->role, ['admin', 'perawat', 'dokter', 'rekam_medis']))
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#adminMenu">
                  <i class="fas fa-cog"></i>
                  <p>Kelola User</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="adminMenu">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="{{ route('users.index') }}">
                        <span class="sub-item">Edit User</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('users.create') }}">
                        <span class="sub-item">Tambah User</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              @endif
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#laporanMenu">
                  <i class="fas fa-book"></i>
                  <p>Laporan</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="laporanMenu">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="{{ route('laporan.kunjungan') }}">
                        <span class="sub-item">Laporan Kunjungan</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('laporan.resep') }}">
                        <span class="sub-item">Laporan Resep Obat</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              {{-- <li class="nav-item">
                <a data-bs-toggle="collapse" href="#tables">
                  <i class="fas fa-table"></i>
                  <p>Tables</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="tables">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="tables/tables.html">
                        <span class="sub-item">Basic Table</span>
                      </a>
                    </li>
                    <li>
                      <a href="tables/datatables.html">
                        <span class="sub-item">Datatables</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#maps">
                  <i class="fas fa-map-marker-alt"></i>
                  <p>Maps</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="maps">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="maps/googlemaps.html">
                        <span class="sub-item">Google Maps</span>
                      </a>
                    </li>
                    <li>
                      <a href="maps/jsvectormap.html">
                        <span class="sub-item">Jsvectormap</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#charts">
                  <i class="far fa-chart-bar"></i>
                  <p>Charts</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="charts">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="charts/charts.html">
                        <span class="sub-item">Chart Js</span>
                      </a>
                    </li>
                    <li>
                      <a href="charts/sparkline.html">
                        <span class="sub-item">Sparkline</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a href="widgets.html">
                  <i class="fas fa-desktop"></i>
                  <p>Widgets</p>
                  <span class="badge badge-success">4</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="../../documentation/index.html">
                  <i class="fas fa-file"></i>
                  <p>Documentation</p>
                  <span class="badge badge-secondary">1</span>
                </a>
              </li>
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#submenu">
                  <i class="fas fa-bars"></i>
                  <p>Menu Levels</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="submenu">
                  <ul class="nav nav-collapse">
                    <li>
                      <a data-bs-toggle="collapse" href="#subnav1">
                        <span class="sub-item">Level 1</span>
                        <span class="caret"></span>
                      </a>
                      <div class="collapse" id="subnav1">
                        <ul class="nav nav-collapse subnav">
                          <li>
                            <a href="#">
                              <span class="sub-item">Level 2</span>
                            </a>
                          </li>
                          <li>
                            <a href="#">
                              <span class="sub-item">Level 2</span>
                            </a>
                          </li>
                        </ul>
                      </div>
                    </li>
                    <li>
                      <a data-bs-toggle="collapse" href="#subnav2">
                        <span class="sub-item">Level 1</span>
                        <span class="caret"></span>
                      </a>
                      <div class="collapse" id="subnav2">
                        <ul class="nav nav-collapse subnav">
                          <li>
                            <a href="#">
                              <span class="sub-item">Level 2</span>
                            </a>
                          </li>
                        </ul>
                      </div>
                    </li>
                    <li>
                      <a href="#">
                        <span class="sub-item">Level 1</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li> --}}
            </ul>
          </div>
        </div>
      </div>