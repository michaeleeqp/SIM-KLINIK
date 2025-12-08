<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\Patient;
use Illuminate\Http\Request;

class KunjunganController extends Controller
{
    public function index()
    {
        // Optional filter by tujuan (poli_tujuan) e.g. ?tujuan=ugd or ?tujuan=umum
        $tujuan = request()->query('tujuan');
        $date = request()->query('date');
        $day = request()->query('day');

        $query = Kunjungan::with('patient')->latest();
        if ($tujuan) {
                $tujuan = strtolower($tujuan);
                if ($tujuan === 'ugd') {
                    $query->whereRaw('LOWER(poli_tujuan) = ?', ['ugd']);
                } elseif ($tujuan === 'umum') {
                    $query->whereRaw('LOWER(poli_tujuan) LIKE ?', ['%umum%']);
                } else {
                    $query->whereRaw('LOWER(poli_tujuan) = ?', [$tujuan]);
                }
        }

        if ($date) {
            $query->whereDate('tanggal_kunjungan', $date);
        }

        if ($day) {
            // Expecting English day names (Monday..Sunday) from query param
            $query->whereRaw("DAYNAME(tanggal_kunjungan) = ?", [$day]);
        }

        $kunjungans = $query->get();

        return view('pages.list_pendaftaran', compact('kunjungans', 'tujuan', 'date', 'day'));
    }

    public function create($patient_id)
    {
        $patient = Patient::findOrFail($patient_id);
        return view('pages.pendaftaran_lama', compact('patient'));
    }

    public function form()
    {
    return view('pages.pendaftaran_lama');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
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

        Kunjungan::create($validated);

        return redirect()
            ->route('list.pendaftaran')
            ->with('success', 'Kunjungan pasien lama berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kunjungan = Kunjungan::with('patient')->findOrFail($id);
        return view('pages.edit_kunjungan', compact('kunjungan'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'poli_tujuan' => 'required|string',
            'tanggal_kunjungan' => 'required|date',
            'jenis_bayar' => 'required|string',
            'no_asuransi' => 'nullable|string',
            
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
}
