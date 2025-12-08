@php
// Helper untuk menentukan menu aktif (opsional, agar rapi)
$isPendaftaranActive = request()->is('pendaftaran/*') || request()->is('list/pendaftaran');
$user = auth()->user();
@endphp

<style>
    .logo-text {
        color: #ffffff !important;
    }
</style>

<div class="sidebar" data-background-color="dark">
  <div class="sidebar-logo">
    <div class="logo-header" data-background-color="dark">
      <a href="/dashboard" class="logo">
        <img
          src="{{asset('template/assets/img/kaiadmin/logo.png')}}"
          alt="navbar brand"
          class="navbar-brand"
          height="40"
        />
        <span class="ms-2 logo-text">RME KLINIK</span>
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
    </div>

  <div class="sidebar-wrapper scrollbar scrollbar-inner">
    <div class="sidebar-content">
      <ul class="nav nav-secondary">
        
        <li class="nav-item @if(request()->is('dashboard')) active @endif">
          <a href="/dashboard">
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

        @if($user && in_array($user->role, ['rekam_medis', 'admin']))
        <li class="nav-item @if($isPendaftaranActive) active @endif">
          <a data-bs-toggle="collapse" href="#base">
            <i class="fas fa-layer-group"></i>
            <p>Pendaftaran</p>
            <span class="caret"></span>
          </a>
          <div class="collapse @if($isPendaftaranActive) show @endif" id="base">
            <ul class="nav nav-collapse">
              <li class="@if(request()->is('pendaftaran/lama*')) active @endif">
                <a href="/pendaftaran/lama">
                  <span class="sub-item">Pasien Lama</span>
                </a>
              </li>
              <li class="@if(request()->is('pendaftaran/baru')) active @endif">
                <a href="/pendaftaran/baru">
                  <span class="sub-item">Pasien Baru</span>
                </a>
              </li>
              <li class="@if(request()->is('list/pendaftaran')) active @endif">
                <a href="/list/pendaftaran">
                  <span class="sub-item">List Pendaftaran</span>
                </a>
              </li>         
            </ul>
          </div>
        </li>

        <li class="nav-item @if(request()->is('master/pasien*')) active @endif">
             <a data-bs-toggle="collapse" href="#sidebarLayouts">
               <i class="fas fa-th-list"></i>
               <p>Pasien</p>
               <span class="caret"></span>
             </a>
             <div class="collapse @if(request()->is('master/pasien*')) show @endif" id="sidebarLayouts">
               <ul class="nav nav-collapse">
                 <li class="@if(request()->is('master/pasien*')) active @endif">
                   <a href="/master/pasien">
                     <span class="sub-item">Master Pasien</span>
                   </a>
                 </li>
               </ul>
             </div>
        </li>
        @endif

        @if($user && in_array($user->role, ['dokter', 'perawat', 'admin']))
        <li class="nav-item @if(request()->is('ugd') || request()->is('umum') || request()->is('ranap') || request()->routeIs('ashuhans.*')) active @endif">
          <a data-bs-toggle="collapse" href="#forms">
            <i class="fas fa-user-md"></i> <p>Poliklinik</p>
            <span class="caret"></span>
          </a>
          <div class="collapse @if(request()->is('ugd') || request()->is('umum') || request()->is('ranap') || request()->routeIs('ashuhans.*')) show @endif" id="forms">
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
              <li class="@if(request()->routeIs('ashuhans.*')) active @endif">
                <a href="{{ route('ashuhans.index') }}">
                  <span class="sub-item">Asuhan Medis</span>
                </a>
              </li>
            </ul>                  
          </div>
        </li>
        @endif

        @if($user && in_array($user->role, ['farmasi', 'admin']))
        <li class="nav-item @if(request()->is('farmasi*') || request()->is('medicines*')) active @endif">
          <a data-bs-toggle="collapse" href="#farmasiMenu">
            <i class="fas fa-pills"></i>
            <p>Farmasi</p>
            <span class="caret"></span>
          </a>
          <div class="collapse @if(request()->is('farmasi*') || request()->is('medicines*')) show @endif" id="farmasiMenu">
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

        @if($user && in_array($user->role, ['rekam_medis', 'admin']))
        <li class="nav-item @if(request()->routeIs('ashuhans.index') || request()->routeIs('ashuhans.show')) active @endif">
          <a data-bs-toggle="collapse" href="#rekamMedis">
            <i class="fas fa-file-medical"></i>
            <p>Rekam Medis</p>
            <span class="caret"></span>
          </a>
          <div class="collapse @if(request()->routeIs('ashuhans.index') || request()->routeIs('ashuhans.show')) show @endif" id="rekamMedis">
            <ul class="nav nav-collapse">
              <li class="@if(request()->routeIs('ashuhans.*')) active @endif">
                <a href="{{ route('ashuhans.index') }}">
                  <span class="sub-item">Data Medis (Asuhan)</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        @endif

        @if($user && in_array($user->role, ['admin']))
        <li class="nav-item @if(request()->is('users*')) active @endif">
          <a data-bs-toggle="collapse" href="#adminMenu">
            <i class="fas fa-cog"></i>
            <p>Kelola User</p>
            <span class="caret"></span>
          </a>
          <div class="collapse @if(request()->is('users*')) show @endif" id="adminMenu">
            <ul class="nav nav-collapse">
              <li class="@if(request()->routeIs('users.index')) active @endif">
                <a href="{{ route('users.index') }}">
                  <span class="sub-item">List User</span>
                </a>
              </li>
              <li class="@if(request()->routeIs('users.create')) active @endif">
                <a href="{{ route('users.create') }}">
                  <span class="sub-item">Tambah User</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        @endif

        @if($user && in_array($user->role, ['rekam_medis', 'farmasi', 'admin']))
        <li class="nav-item @if(request()->is('laporan*')) active @endif">
          <a data-bs-toggle="collapse" href="#laporanMenu">
            <i class="fas fa-book"></i>
            <p>Laporan</p>
            <span class="caret"></span>
          </a>
          <div class="collapse @if(request()->is('laporan*')) show @endif" id="laporanMenu">
            <ul class="nav nav-collapse">
              <li class="@if(request()->routeIs('laporan.kunjungan')) active @endif">
                <a href="{{ route('laporan.kunjungan') }}">
                  <span class="sub-item">Laporan Kunjungan</span>
                </a>
              </li>
              <li class="@if(request()->routeIs('laporan.resep')) active @endif">
                <a href="{{ route('laporan.resep') }}">
                  <span class="sub-item">Laporan Resep Obat</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        @endif

      </ul>
    </div>
  </div>
</div>