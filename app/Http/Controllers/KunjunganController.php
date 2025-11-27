<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\Patient;
use App\Models\Dokter;
use App\Models\UGD;
use Illuminate\Http\Request;

class KunjunganController extends Controller
{
    public function index()
    {
        $kunjungans = Kunjungan::with(['patient', 'dokter'])->latest()->get();
        return view('pages.list_pendaftaran', compact('kunjungans'));
    }

    public function create($patient_id)
    {
        $patient = Patient::findOrFail($patient_id);
        $dokters = Dokter::all(); // plural 🔥
        return view('pages.pendaftaran_lama', compact('patient', 'dokters'));
    }

    public function form()
    {
        $dokters = Dokter::all(); // plural 🔥
        return view('pages.pendaftaran_lama', compact('dokters'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'rujukan_dari' => 'required|string',
            'keterangan_rujukan' => 'required|string',
            'tanggal_kunjungan' => 'required|date',
            'poli_tujuan' => 'required|string',
            'dokter_id' => 'required|exists:dokter,id', 
            'kunjungan' => 'required|string',
            'jenis_bayar' => 'required|string',
            'pj_nama' => 'nullable|string',
            'pj_no_ktp' => 'nullable|digits:16',
            'pj_alamat' => 'nullable|string',
            'pj_no_wa' => 'nullable|digits_between:10,13',
            'catatan_kunjungan' => 'nullable|string',
            'no_asuransi' => 'nullable|string',
        ]);

        Kunjungan::create($validated);

        return redirect()->route('list.pendaftaran')
                         ->with('success', 'Kunjungan pasien lama berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kunjungan = Kunjungan::with(['patient', 'dokter'])->findOrFail($id);
        $dokters = Dokter::all(); // plural 🔥
        return view('pages.edit_kunjungan', compact('kunjungan', 'dokters'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'poli_tujuan' => 'required|string',
            'tanggal_kunjungan' => 'required|date',
            'jenis_bayar' => 'required|string',
            'no_asuransi' => 'nullable|string',
            'dokter_id' => 'required|exists:dokter,id',
        ]);

        $kunjungan = Kunjungan::findOrFail($id);
        $kunjungan->update($validated);

        return redirect()->route('list.pendaftaran')
                         ->with('success', 'Kunjungan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $kunjungan = Kunjungan::findOrFail($id);
        $kunjungan->delete();

        return redirect()->route('list.pendaftaran')
                         ->with('success', 'Kunjungan berhasil dihapus');
    }

    public function ugd()
    {
        $data = Kunjungan::with(['patient', 'dokter'])
            ->where('poli_tujuan', 'UGD')
            ->latest()
            ->get();
        return view('pages.ugd', compact('data'));
    }

    public function getUGDData($id)
    {
        $ugd = Kunjungan::with(['patient', 'dokter'])->find($id);

        if(!$ugd){
            return response()->json(['success'=>false]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'rm'     => $ugd->patient->no_rm,
                'nama'   => $ugd->patient->nama_pasien,
                'lahir'  => $ugd->patient->tanggal_lahir,
                'jk'     => $ugd->patient->jenis_kelamin,
                'goldar' => $ugd->patient->golongan_darah,
                'catatan'=> $ugd->catatan_kunjungan,
                'dokter' => $ugd->dokter ? $ugd->dokter->nama_dokter : '-',
            ]
        ]);
    }

    Public function storeUGD(Request $request)
    {
    $request->validate([
        'kunjungan_id' => 'required|exists:kunjungans,id',
        'keluhan_utama' => 'required|string',
        'riwayat_penyakit' => 'nullable|string',
        'riwayat_alergi' => 'nullable|string',
        'diagnosa_medis' => 'required|string',
        'tindakan_terapi' => 'required|string',
        'catatan_perawatan' => 'nullable|string',
        'sistole' => 'nullable|string',
        'diastole' => 'nullable|string',
        'nadi' => 'nullable|string',
        'suhu' => 'nullable|numeric',
        'respirasi' => 'nullable|string',
    ]);

    UGD::create($request->all());

    return back()->with('success','Asuhan medis berhasil disimpan');
    }
}
