@extends('layout.app')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Detail User</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="#">
                        <i class="fas fa-layer-group"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('users.index') }}">Kelola User</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">{{ $user->name }}</a>
                </li>
            </ul>
        </div>

        {{-- Notifikasi sukses --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Informasi User</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <p class="form-control-static">{{ $user->name }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <p class="form-control-static">{{ $user->email }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <p class="form-control-static">
                                <span class="badge 
                                    @if($user->role === 'admin') bg-danger
                                    @elseif($user->role === 'perawat') bg-info
                                    @elseif($user->role === 'dokter') bg-success
                                    @elseif($user->role === 'rekam_medis') bg-warning
                                    @elseif($user->role === 'farmasi') bg-primary
                                    @else bg-secondary
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                </span>
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Dibuat Pada</label>
                            <p class="form-control-static">{{ $user->created_at->format('d-m-Y H:i') }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Terakhir Update</label>
                            <p class="form-control-static">{{ $user->updated_at->format('d-m-Y H:i') }}</p>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin hapus user ini?')">
                                    <i class="fa fa-trash"></i> Hapus
                                </button>
                            </form>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
