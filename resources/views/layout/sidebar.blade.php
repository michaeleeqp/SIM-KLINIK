@php
    // LOGIKA ACTIVE STATE
    // -------------------
    
    // 1. Dashboard
    $activeDashboard = request()->is('dashboard');

    // 2. Pendaftaran (Parent)
    // Aktif jika url: pendaftaran/lama, pendaftaran/baru, atau list/pendaftaran
    $activePendaftaran = request()->is('pendaftaran/*') || request()->is('list/pendaftaran');

    // 3. Pasien (Parent)
    // Aktif jika url dimulai dengan master/pasien
    $activePasien = request()->is('master/pasien*');

    // 4. Poliklinik (Parent)
    // Aktif jika url dimulai dengan ugd, umum, atau ranap
    $activePoli = request()->is('ugd*') || request()->is('umum*') || request()->is('ranap*');

    // 5. Farmasi (Parent) - Sesuaikan dengan route asli Anda nanti
    $activeFarmasi = request()->is('tables*'); 
@endphp

<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="/dashboard" class="logo">
                <img src="{{asset('template/assets/img/kaiadmin/logo.png')}}" alt="navbar brand" class="navbar-brand" height="40" />
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
                
                {{-- DASHBOARD --}}
                <li class="nav-item {{ $activeDashboard ? 'active' : '' }}">
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

                {{-- PENDAFTARAN --}}
                <li class="nav-item {{ $activePendaftaran ? 'active submenu' : '' }}">
                    <a data-bs-toggle="collapse" href="#base">
                        <i class="fas fa-layer-group"></i>
                        <p>Pendaftaran</p>
                        <span class="caret"></span>
                    </a>
                    {{-- Tambahkan class 'show' agar menu tetap terbuka saat aktif --}}
                    <div class="collapse {{ $activePendaftaran ? 'show' : '' }}" id="base">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('pendaftaran/lama*') ? 'active' : '' }}">
                                <a href="/pendaftaran/lama">
                                    <span class="sub-item">Pasien Lama</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('pendaftaran/baru*') ? 'active' : '' }}">
                                <a href="/pendaftaran/baru">
                                    <span class="sub-item">Pasien Baru</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('list/pendaftaran*') ? 'active' : '' }}">
                                <a href="/list/pendaftaran">
                                    <span class="sub-item">List Pendaftaran</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                {{-- PASIEN --}}
                <li class="nav-item {{ $activePasien ? 'active submenu' : '' }}">
                    <a data-bs-toggle="collapse" href="#sidebarLayouts">
                        <i class="fas fa-th-list"></i>
                        <p>Pasien</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ $activePasien ? 'show' : '' }}" id="sidebarLayouts">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('master/pasien*') ? 'active' : '' }}">
                                <a href="/master/pasien">
                                    <span class="sub-item">Master pasien</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                {{-- POLIKLINIK --}}
                <li class="nav-item {{ $activePoli ? 'active submenu' : '' }}">
                    <a data-bs-toggle="collapse" href="#forms">
                        <i class="fas fa-pen-square"></i>
                        <p>Poliklinik</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ $activePoli ? 'show' : '' }}" id="forms">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('ugd*') ? 'active' : '' }}">
                                <a href="/ugd">
                                    <span class="sub-item">UGD</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('umum*') ? 'active' : '' }}">
                                <a href="/umum">
                                    <span class="sub-item">Klinik Umum</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('ranap*') ? 'active' : '' }}">
                                <a href="/ranap">
                                    <span class="sub-item">Rawat Inap</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                {{-- FARMASI --}}
                <li class="nav-item {{ $activeFarmasi ? 'active submenu' : '' }}">
                    <a data-bs-toggle="collapse" href="#tables">
                        <i class="fas fa-table"></i>
                        <p>Farmasi</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ $activeFarmasi ? 'show' : '' }}" id="tables">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('tables/tables.html') ? 'active' : '' }}">
                                <a href="tables/tables.html">
                                    <span class="sub-item">Pemberian Obat</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('tables/datatables.html') ? 'active' : '' }}">
                                <a href="tables/datatables.html">
                                    <span class="sub-item">Datatables</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

            </ul>
        </div>
    </div>
</div>