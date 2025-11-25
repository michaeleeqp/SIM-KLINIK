<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::orderBy('id', 'desc')->get();

        return view('pages.master_pasien', compact('patients'));
    }
    public function create()
    {
        $lastPatient = Patient::orderBy('no_rm', 'desc')->first();
        $lastNoRm = $lastPatient ? $lastPatient->no_rm : null;

        return view('pages.pendaftaran_baru', compact('lastNoRm'));
    }

    public function edit($id)
    {
        $patient = Patient::findOrFail($id);
        return view('pages.edit_patient', compact('patient'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_pasien' => 'required|string',
            'no_ktp' => 'nullable|digits:16',
            'alamat' => 'required|string',
            // tambahkan field pasien yang lain
        ]);

        $patient = Patient::findOrFail($id);
        $patient->update($validated);

        return redirect()->route('master.pasien')->with('success', 'Data pasien berhasil diperbarui');
    }

    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();

        return redirect()->route('master.pasien')->with('success', 'Pasien berhasil dihapus');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // 🔹 Validasi PASIEN
            $validatedPatient = $request->validate([
                'nama_pasien' => 'required|string|max:100',
                'no_ktp' => 'required|digits:16|unique:patients,no_ktp',
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'tanggal_lahir' => 'required|date',
                'agama' => 'required|string',
                'pendidikan' => 'required|string',
                'status_perkawinan' => 'required|string',
                'status_keluarga' => 'required|string',
                'golongan_darah' => 'required|string',
                'alamat' => 'required|string',
                'no_wa' => 'required|digits_between:10,13',
                'pekerjaan' => 'required|string',
                'provinsi_id' => 'required|string|min:1',
                'kabupaten_id' => 'required|string|min:1',
                'kecamatan_id' => 'required|string|min:1',
                'desa_id' => 'required|string|min:1',
            ]);

            // 🔹 Nomor RM otomatis
            $lastPatient = Patient::orderBy('no_rm', 'desc')->first();
            $validatedPatient['no_rm'] = $lastPatient
                ? str_pad(((int)$lastPatient->no_rm) + 1, 6, '0', STR_PAD_LEFT)
                : '000001';

            // 🔹 Simpan pasien
            $patient = Patient::create($validatedPatient);

            // 🔹 Validasi KUNJUNGAN
            $validatedKunjungan = $request->validate([
                'rujukan_dari' => 'required|string',
                'keterangan_rujukan' => 'required|string',
                'tanggal_kunjungan' => 'required|date',
                'poli_tujuan' => 'required|string',
                'jadwal_dokter' => 'required|string',
                'kunjungan' => 'required|string',
                'jenis_bayar' => 'required|string',
                'pj_nama' => 'nullable|string',
                'pj_no_ktp' => 'nullable|digits:16',
                'pj_alamat' => 'nullable|string',
                'pj_no_wa' => 'nullable|digits_between:10,13',
                'catatan_kunjungan' => 'nullable|string',
                'no_asuransi' => 'nullable|string',
            ]);

            $validatedKunjungan['patient_id'] = $patient->id;

            // 🔹 Simpan kunjungan pertama
            Kunjungan::create($validatedKunjungan);

            DB::commit();

            return redirect()
                ->route('list.pendaftaran')
                ->with('success', 'Pendaftaran pasien baru + kunjungan berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    public function search(Request $request)
    {
        $keyword = $request->q ?? $request->keyword;

        $patient = Patient::where('no_rm', 'like', "%{$keyword}%")
            ->orWhere('no_ktp', 'like', "%{$keyword}%")
            ->first();

        if (!$patient) {
            return response()->json([
                'success' => false,
                'message' => 'Pasien tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $patient
        ]);
    }
}
