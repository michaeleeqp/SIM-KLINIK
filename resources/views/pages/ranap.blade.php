@extends('layout.app')

@section('content') < div class = "container" > <div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Poliklinik</h3>
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
                <a href="#">Klinik Umum</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Form Klinik Umum</a>
            </li>
        </ul>
    </div>

    <div class="row">

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Identitas Pasien</div>
                </div>

                <form id="formIdentitas">
                    <input type="hidden" name="patient_id" id="patient_id">

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="no_rm">No RM</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="no_rm" name="no_rm"></div>
                                        </div>
                                        <div class="form-group">
                                            <label>Nama Pasien</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                name="nama_pasien"
                                                id="nama_pasien"
                                                readonly="readonly"></div>
                                        </div>

                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label for="tanggal_lahir">Tanggal Lahir</label>
                                                <div class="input-group mb-3">
                                                    <input
                                                        type="date"
                                                        class="form-control"
                                                        id="tanggal_lahir"
                                                        name="tanggal_lahir"
                                                        max="{{ date('Y-m-d') }}"
                                                        onchange="hitungUmur()"
                                                        required="required"/>
                                                    <span class="input-group-text" id="umur_display">Umur: -</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Jenis Kelamin</label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    name="jenis_kelamin"
                                                    id="jenis_kelamin"
                                                    readonly="readonly"></div>
                                            </div>

                                            <div class="col-md-6 col-lg-4">
                                                <div class="form-group">
                                                    <label>Golongan Darah</label>
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        name="golongan_darah"
                                                        id="golongan_darah"
                                                        readonly="readonly"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">Asuhan Medis</div>
                                </div>

                                <div class="card-body">

                                    <div class="form-group">
                                        <label for="keluhan_utama">Keluhan Utama</label>
                                        <input type="text" class="form-control" id="keluhan_utama" name="keluhan_utama"></div>

                                        <div class="form-group">
                                            <label for="riwayat_penyakit">Riwayat Penyakit</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                id="riwayat_penyakit"
                                                name="riwayat_penyakit"></div>

                                            <div class="form-group">
                                                <label for="riwayat_alergi">Riwayat Alergi</label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    id="riwayat_alergi"
                                                    name="riwayat_alergi"></div>

                                                <div class="form-group">
                                                    <label for="diagnosa_medis">Diagnosa Medis</label>
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        id="diagnosa_medis"
                                                        name="diagnosa_medis"></div>

                                                    <div class="form-group">
                                                        <label for="tindakan_terapi">Tindakan / Terapi</label>
                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            id="tindakan_terapi"
                                                            name="tindakan_terapi"></div>

                                                        <div class="form-group">
                                                            <label for="catatan_perawatan">Catatan Perawatan</label>
                                                            <textarea
                                                                class="form-control"
                                                                id="catatan_perawatan"
                                                                name="catatan_perawatan"
                                                                rows="3"></textarea>
                                                        </div>

                                                        <br>
                                                            <h5 class="fw-bold mb-3 border-bottom pb-2">Tanda-Tanda Vital</h5>

                                                            <div class="row">
                                                                <div class="col-12 col-md-6 col-lg-3">
                                                                    <div class="form-group">
                                                                        <label for="tekanan_darah">Tekanan Darah (mm/Hg)</label>
                                                                        <input
                                                                            type="text"
                                                                            class="form-control"
                                                                            id="tekanan_darah"
                                                                            name="tekanan_darah"
                                                                            required="required"></div>
                                                                    </div>

                                                                    <div class="col-12 col-md-6 col-lg-3">
                                                                        <div class="form-group">
                                                                            <label for="nadi">Nadi (X/Menit)</label>
                                                                            <input
                                                                                type="number"
                                                                                class="form-control"
                                                                                id="nadi"
                                                                                name="nadi"
                                                                                required="required"></div>
                                                                        </div>

                                                                        <div class="col-12 col-md-6 col-lg-3">
                                                                            <div class="form-group">
                                                                                <label for="suhu">Suhu (°C)</label>
                                                                                <input
                                                                                    type="number"
                                                                                    step="0.1"
                                                                                    class="form-control"
                                                                                    id="suhu"
                                                                                    name="suhu"
                                                                                    required="required"></div>
                                                                            </div>

                                                                            <div class="col-12 col-md-6 col-lg-3">
                                                                                <div class="form-group">
                                                                                    <label for="respirasi">Respirasi (X/Menit)</label>
                                                                                    <input
                                                                                        type="number"
                                                                                        class="form-control"
                                                                                        id="respirasi"
                                                                                        name="respirasi"
                                                                                        required="required"></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="card-action d-flex gap-2">
                                                                            <button type="submit" class="btn btn-success">Submit</button>
                                                                            <button type="reset" class="btn btn-danger">Cancel</button>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        @endsection @push('scripts')
                                        <script>
                                            // Saya sesuaikan nama fungsinya agar cocok dengan "onchange" di HTML
                                            function hitungUmur() {
                                                const dateStr = document
                                                    .getElementById('tanggal_lahir')
                                                    .value;
                                                const display = document.getElementById('umur_display');

                                                if (!display) 
                                                    return;
                                                if (!dateStr) {
                                                    display.textContent = 'Umur: -';
                                                    return;
                                                }

                                                const tgl = new Date(dateStr);
                                                const now = new Date();

                                                if (tgl > now) {
                                                    display.textContent = 'Umur: Tanggal Tidak Valid';
                                                    return;
                                                }

                                                let y = now.getFullYear() - tgl.getFullYear();
                                                let m = now.getMonth() - tgl.getMonth();
                                                let d = now.getDate() - tgl.getDate();

                                                if (d < 0) {
                                                    m--;
                                                    d += new Date(now.getFullYear(), now.getMonth(), 0).getDate();
                                                }
                                                if (m < 0) {
                                                    y--;
                                                    m += 12;
                                                }

                                                display.textContent = Umur: ${y}y ${m}m ${d}d;
                                            }
                                        </script>
                                        @endpush