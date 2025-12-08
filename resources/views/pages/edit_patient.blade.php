@extends('layout.app')

@section('content')
<div class="container">
	<div class="page-inner">
		<div class="page-header">
			<h3 class="fw-bold mb-3">Edit Pasien</h3>
			<ul class="breadcrumbs mb-3">
				<li class="nav-home">
					<a href="/dashboard">
						<i class="icon-home"></i>
					</a>
				</li>
				<li class="separator"><i class="icon-arrow-right"></i></li>
				<li class="nav-item"><a href="#">Pasien</a></li>
				<li class="separator"><i class="icon-arrow-right"></i></li>
				<li class="nav-item"><a href="#">Edit Pasien</a></li>
			</ul>
		</div>

		<div class="row">
			<div class="col-md-8">
				<div class="card">
					<div class="card-header"><h4 class="card-title">Form Edit Pasien</h4></div>
					<div class="card-body">
						@if($errors->any())
							<div class="alert alert-danger">
								<ul class="mb-0">
									@foreach($errors->all() as $err)
										<li>{{ $err }}</li>
									@endforeach
								</ul>
							</div>
						@endif

						<form action="{{ route('patient.update', $patient->id) }}" method="POST">
							@csrf
							@method('PUT')

							<div class="mb-3">
								<label class="form-label">No RM</label>
								<input type="text" name="no_rm" class="form-control" value="{{ old('no_rm', $patient->no_rm) }}" readonly>
							</div>

							<div class="mb-3">
								<label class="form-label">Nama Pasien</label>
								<input type="text" name="nama_pasien" class="form-control" value="{{ old('nama_pasien', $patient->nama_pasien) }}" required>
							</div>

							<div class="mb-3">
								<label class="form-label">No KTP</label>
								<input id="noktp" type="text" name="no_ktp" class="form-control" value="{{ old('no_ktp', $patient->no_ktp) }}">
							</div>

							<div class="row">
								<div class="col-md-6 mb-3">
									<label class="form-label">Jenis Kelamin</label>
									<select name="jenis_kelamin" class="form-control" required>
										<option value="" disabled>-- Pilih Jenis Kelamin --</option>
										@php($selJenis = strtolower(trim((string) old('jenis_kelamin', $patient->jenis_kelamin ?? ''))))
										<option value="Laki-laki" @selected($selJenis == strtolower('Laki-laki'))>Laki-laki</option>
										<option value="Perempuan" @selected($selJenis == strtolower('Perempuan'))>Perempuan</option>
									</select>
								</div>

								<div class="col-md-6 mb-3">
									<label class="form-label">Tanggal Lahir</label>
									<div class="input-group mb-3">
										<input id="tanggal_lahir" type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $patient->tanggal_lahir ? \Carbon\Carbon::parse($patient->tanggal_lahir)->format('Y-m-d') : '') }}" max="{{ date('Y-m-d') }}" onchange="hitungUmur()">
										<span class="input-group-text" id="umur_display">Umur: 0y 0m 0d</span>
									</div>
								</div>
							</div>

							<div class="mb-3">
								<label class="form-label">Golongan Darah</label>
								<select name="golongan_darah" class="form-control">
									<option value="" disabled>-- Pilih Golongan Darah --</option>
									@php($selGD = strtolower(trim((string) old('golongan_darah', $patient->golongan_darah ?? ''))))
									@foreach(['A','B','AB','O','Tidak Tahu'] as $gd)
										<option value="{{ $gd }}" @selected($selGD == strtolower($gd))>{{ $gd }}</option>
									@endforeach
								</select>
							</div>

							<div class="mb-3">
								<label class="form-label">Alamat</label>
								<textarea name="alamat" class="form-control" rows="3">{{ old('alamat', $patient->alamat) }}</textarea>
							</div>

							<div class="row">
								<div class="col-md-6 mb-3">
									<label class="form-label">No WA</label>
									<input id="nowa" type="text" name="no_wa" class="form-control" value="{{ old('no_wa', $patient->no_wa) }}">
								</div>
								<div class="col-md-6 mb-3">
									<label class="form-label">Pekerjaan</label>
									<select name="pekerjaan" class="form-control">
										<option value="" disabled>-- Pilih Pekerjaan --</option>
										@php($selPkj = strtolower(trim((string) old('pekerjaan', $patient->pekerjaan ?? ''))))
										@foreach(['Pegawai BUMN','PNS','TNI','Polisi','Karyawan Swasta','Petani','Nelayan','Wiraswasta','Pelajar / Mahasiswa','Ibu Rumah Tangga','Tidak Bekerja','Lainnya'] as $job)
											<option value="{{ $job }}" @selected($selPkj == strtolower($job))>{{ $job }}</option>
										@endforeach
									</select>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6 mb-3">
									<label class="form-label">Agama</label>
									<select name="agama" class="form-control">
										<option value="" disabled>-- Pilih Agama --</option>
										@php($selAgama = strtolower(trim((string) old('agama', $patient->agama ?? ''))))
										@foreach(['Islam','Kristen','Katolik','Hindu','Budha','Konghucu'] as $agama)
											<option value="{{ $agama }}" @selected($selAgama == strtolower($agama))>{{ $agama }}</option>
										@endforeach
									</select>
								</div>
								<div class="col-md-6 mb-3">
									<label class="form-label">Pendidikan</label>
									<select name="pendidikan" class="form-control">
										<option value="" disabled>-- Pilih Pendidikan --</option>
										@php($selPend = strtolower(trim((string) old('pendidikan', $patient->pendidikan ?? ''))))
										@foreach(['Tidak Sekolah','Belum / Tidak Tamat SD','Tamat SD','Tamat SLTP / SMP/ Sederajat','Tamat SLTA / SMA / SMK / Sederajat','Diploma','Sarjana','Pasca Sarjana'] as $pend)
											<option value="{{ $pend }}" @selected($selPend == strtolower($pend))>{{ $pend }}</option>
										@endforeach
									</select>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6 mb-3">
									<label class="form-label">Status Perkawinan</label>
									<select name="status_perkawinan" class="form-control">
										<option value="" disabled>-- Pilih Status --</option>
										@php($selSt = strtolower(trim((string) old('status_perkawinan', $patient->status_perkawinan ?? ''))))
										@foreach(['Belum Kawin','Kawin','Cerai Hidup','Cerai Mati'] as $st)
											<option value="{{ $st }}" @selected($selSt == strtolower($st))>{{ $st }}</option>
										@endforeach
									</select>
								</div>
								<div class="col-md-6 mb-3">
									<label class="form-label">Status Keluarga</label>
									<select name="status_keluarga" class="form-control">
										<option value="" disabled>-- Pilih Status Keluarga --</option>
										@php($selSk = strtolower(trim((string) old('status_keluarga', $patient->status_keluarga ?? ''))))
										@foreach(['Tuan','Nyonya','Anak','Lainnya'] as $sk)
											<option value="{{ $sk }}" @selected($selSk == strtolower($sk))>{{ $sk }}</option>
										@endforeach
									</select>
								</div>
							</div>

							<div class="row">
								<div class="col-md-3 mb-3">
									<label class="form-label">Provinsi</label>
									<select id="provinsi" name="provinsi_id" class="form-control" required>
										<option value="" disabled selected hidden>Pilih Provinsi</option>
									</select>
								</div>
								<div class="col-md-3 mb-3">
									<label class="form-label">Kabupaten</label>
									<select id="kabupaten" name="kabupaten_id" class="form-control" required>
										<option value="" disabled selected hidden>Pilih Kabupaten</option>
										<option>-</option>
									</select>
								</div>
								<div class="col-md-3 mb-3">
									<label class="form-label">Kecamatan</label>
									<select id="kecamatan" name="kecamatan_id" class="form-control" required>
										<option value="" disabled selected hidden>Pilih Kecamatan</option>
										<option>-</option>
									</select>
								</div>
								<div class="col-md-3 mb-3">
									<label class="form-label">Desa</label>
									<select id="desa" name="desa_id" class="form-control" required>
										<option value="" disabled selected hidden>Pilih Desa</option>
										<option>-</option>
									</select>
								</div>
							</div>

							<div class="d-flex gap-2">
								<a href="{{ route('master.pasien') }}" class="btn btn-ghost-secondary">Batal</a>
								<button type="submit" class="btn btn-primary">Simpan Perubahan</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script>
function cleanAndLimitKTP(event) {
	let value = event.target.value;
	value = value.replace(/[^0-9]/g, '');
	value = value.slice(0, 16);
	event.target.value = value;
}

function cleanAndLimitWAInput(event) {
	let value = event.target.value;
	value = value.replace(/[^0-9]/g, '');
	value = value.slice(0, 13);
	event.target.value = value;
}

function hitungUmur() {
	const tanggalLahirInput = document.getElementById('tanggal_lahir');
	const displayUmur = document.getElementById('umur_display');
	const tanggalLahirValue = tanggalLahirInput ? tanggalLahirInput.value : null;

	if (!tanggalLahirValue) {
		if (displayUmur) displayUmur.textContent = "Umur: 0y 0m 0d";
		return;
	}

	const tglLahir = new Date(tanggalLahirValue);
	const hariIni = new Date();
	if (tglLahir > hariIni) {
		if (displayUmur) displayUmur.textContent = "Umur: Tanggal Tidak Valid";
		return;
	}

	let tahun = hariIni.getFullYear() - tglLahir.getFullYear();
	let bulan = hariIni.getMonth() - tglLahir.getMonth();
	let hari = hariIni.getDate() - tglLahir.getDate();

	if (hari < 0) {
		bulan--;
		hari += new Date(hariIni.getFullYear(), hariIni.getMonth(), 0).getDate();
	}
	if (bulan < 0) {
		tahun--;
		bulan += 12;
	}
	if (displayUmur) displayUmur.textContent = `Umur: ${tahun}y ${bulan}m ${hari}d`;
}

document.addEventListener('DOMContentLoaded', function () {
	const inputKTP = document.getElementById('noktp');
	const inputWA = document.getElementById('nowa');
	if (inputKTP) inputKTP.addEventListener('input', cleanAndLimitKTP);
	if (inputWA) inputWA.addEventListener('input', cleanAndLimitWAInput);

	const tanggalLahirInput = document.getElementById('tanggal_lahir');
	if (tanggalLahirInput) {
		tanggalLahirInput.addEventListener('change', hitungUmur);
		hitungUmur();
	}
});
</script>
@include('partials.wilayah_scripts', ['initialProv' => old('provinsi_id', $patient->provinsi_id), 'initialKab' => old('kabupaten_id', $patient->kabupaten_id), 'initialKec' => old('kecamatan_id', $patient->kecamatan_id), 'initialDesa' => old('desa_id', $patient->desa_id)])
@endpush

