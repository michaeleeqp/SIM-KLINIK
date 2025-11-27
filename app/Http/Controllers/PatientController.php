<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Kunjungan;
use App\Models\Dokter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

        $dokters = Dokter::all();

        return view('pages.pendaftaran_baru', compact('lastNoRm','dokters'));
    }

        public function edit($id)
    {
        $patient = Patient::findOrFail($id);

        try {
            $provinsi   = Http::timeout(5)->get("https://emsifa.github.io/api-wilayah-indonesia/api/provinces.json")->json();
            $kabupaten  = Http::timeout(5)->get("https://emsifa.github.io/api-wilayah-indonesia/api/regencies/{$patient->provinsi_id}.json")->json();
            $kecamatan  = Http::timeout(5)->get("https://emsifa.github.io/api-wilayah-indonesia/api/districts/{$patient->kabupaten_id}.json")->json();
            $desa       = Http::timeout(5)->get("https://emsifa.github.io/api-wilayah-indonesia/api/villages/{$patient->kecamatan_id}.json")->json();
        } catch (\Exception $e) {
            // fallback agar halaman tetap terbuka
            $provinsi = $kabupaten = $kecamatan = $desa = [];
            
            return back()->with('error', 'Gagal memuat wilayah! Pastikan koneksi internet aktif.');
        }

        // Ambil kunjungan terakhir pasien
        $kunjunganTerakhir = Kunjungan::where('patient_id', $id)->latest()->first()?->tanggal_kunjungan;

        return view('pages.edit_patient', compact(
            'patient','provinsi','kabupaten','kecamatan','desa','kunjunganTerakhir'
        ));
    }


    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);

        $patient->update([
            'nama_pasien'        => $request->nama_pasien,
            'no_ktp'             => $request->no_ktp,
            'agama'              => $request->agama,
            'pendidikan'         => $request->pendidikan,
            'status_perkawinan'  => $request->status_perkawinan,
            'status_keluarga'    => $request->status_keluarga,
            'tanggal_lahir'      => $request->tanggal_lahir,
            'jenis_kelamin'      => $request->jenis_kelamin,
            'golongan_darah'     => $request->golongan_darah,
            'alamat'             => $request->alamat,
            'no_wa'              => $request->no_wa,
            'pekerjaan'          => $request->pekerjaan,
            'provinsi_id'        => $request->provinsi_id,
            'kabupaten_id'       => $request->kabupaten_id,
            'kecamatan_id'       => $request->kecamatan_id,
            
        ]);

        return redirect()->route('master.pasien')->with('success','Data pasien berhasil diupdate!');
    }

    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();

        return redirect()->route('master.pasien')->with('success','Pasien berhasil dihapus');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

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
                'provinsi_id' => 'required|string',
                'kabupaten_id' => 'required|string',
                'kecamatan_id' => 'required|string',
                'desa_id' => 'required|string',
            ]);

            $lastPatient = Patient::orderBy('no_rm','desc')->first();
            $validatedPatient['no_rm'] = $lastPatient
                ? str_pad(((int)$lastPatient->no_rm)+1,6,'0',STR_PAD_LEFT)
                : '000001';

            $patient = Patient::create($validatedPatient);

            $validatedKunjungan = $request->validate([
                'rujukan_dari'=>'required|string',
                'keterangan_rujukan'=>'required|string',
                'tanggal_kunjungan'=>'required|date',
                'poli_tujuan'=>'required|string',
                'dokter_id'=>'required|string',
                'kunjungan'=>'required|string',
                'jenis_bayar'=>'required|string',
                'pj_nama'=>'nullable|string',
                'pj_no_ktp'=>'nullable|digits:16',
                'pj_alamat'=>'nullable|string',
                'pj_no_wa'=>'nullable|digits_between:10,13',
                'catatan_kunjungan'=>'nullable|string',
                'no_asuransi'=>'nullable|string'
            ]);

            $validatedKunjungan['patient_id'] = $patient->id;
            Kunjungan::create($validatedKunjungan);

            DB::commit();
            return redirect()->route('list.pendaftaran')->with('success','Pasien + kunjungan tersimpan');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error'=>$e->getMessage()]);
        }
    }

    public function search(Request $request)
    {
        $keyword = $request->q ?? $request->keyword;

        $patient = Patient::where('no_rm','like',"%{$keyword}%")
                          ->orWhere('no_ktp','like',"%{$keyword}%")
                          ->first();

        if(!$patient){
            return response()->json(['success'=>false,'message'=>'Tidak ditemukan'],404);
        }

        return response()->json(['success'=>true,'data'=>$patient]);
    }

}
