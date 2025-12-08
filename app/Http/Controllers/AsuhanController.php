<?php

namespace App\Http\Controllers;

use App\Models\Asuhan;
use App\Models\Patient;
use App\Models\Kunjungan;
use App\Models\Medicine;
use App\Models\PrescriptionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AsuhanController extends Controller
{
    /**
     * Display a listing of ashuhans
     */
    public function index(Request $request)
    {
        $poli = $request->query('poli');
        $date = $request->query('date');
        $day = $request->query('day');

        $query = Asuhan::with(['patient', 'user']);

        if ($poli && in_array($poli, ['ugd', 'umum', 'rawat_inap'])) {
            $query->where('poli_tujuan', $poli);
        }

        if ($date) {
            $query->whereDate('tanggal_asuhan', $date);
        }

        if ($day) {
            $query->whereRaw("DAYNAME(tanggal_asuhan) = ?", [$day]);
        }

        $ashuhans = $query->orderBy('tanggal_asuhan', 'desc')->paginate(15);

        return view('pages.ashuhans.index', compact('ashuhans', 'poli', 'date', 'day'));
    }

    /**
     * Sync prescription items for given asuhan from resep string (JSON or text).
     */
    private function syncPrescriptionItems(Asuhan $asuhan, $resepString = null)
    {
        // Remove existing items
        PrescriptionItem::where('asuhan_id', $asuhan->id)->delete();

        if (!$resepString) {
            return;
        }

        $items = null;
        try {
            $decoded = json_decode($resepString, true);
            if (is_array($decoded)) {
                $items = $decoded;
            }
        } catch (\Throwable $e) {
            $items = null;
        }

        if (is_array($items)) {
            foreach ($items as $it) {
                $medicineId = null;
                if (!empty($it['id']) && is_numeric($it['id'])) {
                    $m = Medicine::find($it['id']);
                    if ($m) $medicineId = $m->id;
                }

                PrescriptionItem::create([
                    'asuhan_id' => $asuhan->id,
                    'medicine_id' => $medicineId,
                    'name' => $it['name'] ?? ($it['nama'] ?? 'Unknown'),
                    'unit' => $it['unit'] ?? null,
                    'price' => isset($it['price']) ? floatval($it['price']) : null,
                    'qty' => isset($it['qty']) ? intval($it['qty']) : 1,
                    'note' => $it['note'] ?? ($it['catatan'] ?? null),
                ]);
            }
        } else {
            // Fallback: store entire resep text as one item
            PrescriptionItem::create([
                'asuhan_id' => $asuhan->id,
                'medicine_id' => null,
                'name' => substr($resepString, 0, 191),
                'unit' => null,
                'price' => null,
                'qty' => 1,
                'note' => null,
            ]);
        }
    }

    /**
     * Show the form for creating a new asuhan
     */
    public function create()
    {
        return view('pages.ashuhans.create');
    }

    /**
     * Store a newly created asuhan
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'no_rm' => 'required|string|exists:patients,no_rm',
            'poli_tujuan' => 'required|in:ugd,umum,rawat_inap',
            'tanggal_asuhan' => 'required|date',
            'admission_date' => 'nullable|date_format:Y-m-d\TH:i',
            'discharge_date' => 'nullable|date_format:Y-m-d\TH:i',
            'keluhan_utama' => 'required|string',
            'riwayat_penyakit' => 'nullable|string',
            'riwayat_alergi' => 'nullable|string',
            'diagnosa_medis' => 'required|string',
            'tindakan_terapi' => 'nullable|string',
            'resep' => 'nullable|string',
            'catatan_perawatan' => 'nullable|string',
            'tekanan_darah' => 'nullable|string|max:50',
            'nadi' => 'nullable|integer|min:0|max:300',
            'suhu' => 'nullable|numeric|min:35|max:45',
            'respirasi' => 'nullable|integer|min:0|max:100',
            'status' => 'required|in:draft,final',
        ]);

        $patient = Patient::where('no_rm', $data['no_rm'])->first();
        
        if (!$patient) {
            return back()->withErrors(['no_rm' => 'Pasien dengan No RM ini tidak ditemukan.'])->withInput();
        }

        try {
            $asuhan = Asuhan::create([
                'patient_id' => $patient->id,
                'user_id' => Auth::id(),
                'poli_tujuan' => $data['poli_tujuan'],
                'tanggal_asuhan' => $data['tanggal_asuhan'],
                'admission_date' => $data['admission_date'] ?? null,
                'discharge_date' => $data['discharge_date'] ?? null,
                'keluhan_utama' => $data['keluhan_utama'],
                'riwayat_penyakit' => $data['riwayat_penyakit'] ?? null,
                'riwayat_alergi' => $data['riwayat_alergi'] ?? null,
                'diagnosa_medis' => $data['diagnosa_medis'],
                'tindakan_terapi' => $data['tindakan_terapi'] ?? null,
                'resep' => $data['resep'] ?? null,
                'catatan_perawatan' => $data['catatan_perawatan'] ?? null,
                'tekanan_darah' => $data['tekanan_darah'] ?? null,
                'nadi' => $data['nadi'] ?? null,
                'suhu' => $data['suhu'] ?? null,
                'respirasi' => $data['respirasi'] ?? null,
                'status' => $data['status'],
            ]);

            // Sync prescription items (if resep provided)
            $this->syncPrescriptionItems($asuhan, $data['resep'] ?? null);

            // If coming from poliklinik form, redirect to ashuhans list
            // Otherwise redirect to show detail
            if ($request->has('from_poliklinik') || $data['status'] === 'final') {
                return redirect()->route('ashuhans.index', ['poli' => $data['poli_tujuan']])
                               ->with('success', 'Asuhan medis berhasil disimpan.');
            }

            return redirect()->route('ashuhans.show', $asuhan->id)
                           ->with('success', 'Asuhan medis berhasil dibuat.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menyimpan asuhan: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified asuhan
     */
    public function show(Asuhan $asuhan)
    {
        $asuhan->load(['patient', 'user', 'prescriptionItems']);
        return view('pages.ashuhans.show', compact('asuhan'));
    }

    /**
     * Show the form for editing the specified asuhan
     */
    public function edit(Asuhan $asuhan)
    {
        $asuhan->load(['patient', 'user', 'prescriptionItems']);
        $medicines = Medicine::orderBy('name')->get();
        return view('pages.ashuhans.edit', compact('asuhan', 'medicines'));
    }

    /**
     * Update the specified asuhan in storage
     */
    public function update(Request $request, Asuhan $asuhan)
    {
        $data = $request->validate([
            'no_rm' => 'required|string|exists:patients,no_rm',
            'poli_tujuan' => 'required|in:ugd,umum,rawat_inap',
            'tanggal_asuhan' => 'required|date',
            'keluhan_utama' => 'required|string',
            'riwayat_penyakit' => 'nullable|string',
            'riwayat_alergi' => 'nullable|string',
            'diagnosa_medis' => 'required|string',
            'tindakan_terapi' => 'nullable|string',
            'resep' => 'nullable|string',
            'catatan_perawatan' => 'nullable|string',
            'tekanan_darah' => 'nullable|string|max:50',
            'nadi' => 'nullable|integer|min:0|max:300',
            'suhu' => 'nullable|numeric|min:35|max:45',
            'respirasi' => 'nullable|integer|min:0|max:100',
            'status' => 'required|in:draft,final',
        ]);

        try {
            // Ensure only allowed fields are updated
            $asuhan->update(array_merge($data, ['resep' => $data['resep'] ?? $asuhan->resep]));

            // Sync prescription items after update
            $this->syncPrescriptionItems($asuhan, $data['resep'] ?? null);

            return redirect()->route('ashuhans.show', $asuhan->id)
                           ->with('success', 'Asuhan medis berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal memperbarui asuhan: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified asuhan from storage
     */
    public function destroy(Asuhan $asuhan)
    {
        try {
            $poli = $asuhan->poli_tujuan;
            $asuhan->delete();
            return redirect()->route('ashuhans.index', ['poli' => $poli])
                           ->with('success', 'Asuhan medis berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menghapus asuhan: ' . $e->getMessage()]);
        }
    }

    /**
     * Rujuk pasien ke rawat inap. Hanya dapat dilakukan jika asuhan berasal dari poli UGD atau Umum.
     */
    public function rujuk(Request $request, Asuhan $asuhan)
    {
        // Pastikan asuhan sudah diload patient
        $asuhan->load('patient');

        if (strtolower($asuhan->poli_tujuan) !== 'ugd' && strtolower($asuhan->poli_tujuan) !== 'umum') {
            return back()->withErrors(['error' => 'Hanya pasien dari poli UGD atau Umum yang dapat dirujuk ke rawat inap.']);
        }

        try {
            // Cari kunjungan terakhir pasien, jika ada update poli_tujuan menjadi rawat_inap
            $kunjungan = Kunjungan::where('patient_id', $asuhan->patient_id)
                ->orderBy('tanggal_kunjungan', 'desc')
                ->first();

            if ($kunjungan) {
                $kunjungan->poli_tujuan = 'rawat_inap';
                $kunjungan->save();
            } else {
                // Jika tidak ada kunjungan, buat kunjungan baru untuk mencatat rujukan
                $kunjungan = Kunjungan::create([
                    'patient_id' => $asuhan->patient_id,
                    'tanggal_kunjungan' => now()->toDateString(),
                    'poli_tujuan' => 'rawat_inap',
                    // Provide safe default values for required fields so automatic creation doesn't fail
                    'jadwal_dokter' => 'TBD',
                    'kunjungan' => 'Rujukan',
                    'jenis_bayar' => 'Umum',
                    'rujukan_dari' => 'Poliklinik',
                    'keterangan_rujukan' => 'Auto-created dari poliklinik rujukan',
                ]);
            }

                // Update juga record asuhan supaya mencatat bahwa pasien telah dirujuk ke rawat_inap
                $asuhan->poli_tujuan = 'Rawat_inap';
                $asuhan->save();

            return redirect()->route('ashuhans.show', $asuhan->id)
                ->with('success', 'Pasien berhasil dirujuk ke rawat inap.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal merujuk pasien: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Store assessment submitted from poliklinik pages (redirect from poliklinik form)
     */
    public function storeFromPoliklinik(Request $request, $poli)
    {
        $poli = strtolower($poli);
        if (!in_array($poli, ['ugd','umum','rawat_inap'])) {
            return back()->withErrors(['poli' => 'Tujuan poliklinik tidak dikenal.']);
        }

        // Validate required fields from poliklinik form
        $data = $request->validate([
            'no_rm' => 'required|string|exists:patients,no_rm',
            'keluhan_utama' => 'required|string',
            'diagnosa_medis' => 'required|string',
            'riwayat_penyakit' => 'nullable|string',
            'riwayat_alergi' => 'nullable|string',
            'tindakan_terapi' => 'nullable|string',
            'catatan_perawatan' => 'nullable|string',
            // Accept resep and inpatient dates coming from poliklinik/rawat_inap form
            'resep' => 'nullable|string',
            'admission_date' => 'nullable|date_format:Y-m-d\TH:i',
            'discharge_date' => 'nullable|date_format:Y-m-d\TH:i',
            'tekanan_darah' => 'nullable|string|max:50',
            'nadi' => 'nullable|integer|min:0|max:300',
            'suhu' => 'nullable|numeric|min:35|max:45',
            'respirasi' => 'nullable|integer|min:0|max:100',
        ]);

        // Check if patient exists
        $patient = Patient::where('no_rm', $data['no_rm'])->first();
        if (!$patient) {
            return back()->withErrors(['no_rm' => 'Pasien dengan No RM ini tidak ditemukan.'])->withInput();
        }

        try {
            // Create asuhan with poli_tujuan, tanggal_asuhan, and status
            $asuhan = Asuhan::create([
                'patient_id' => $patient->id,
                'user_id' => Auth::id(),
                'poli_tujuan' => $poli,
                'tanggal_asuhan' => now()->toDateString(),
                'keluhan_utama' => $data['keluhan_utama'],
                'riwayat_penyakit' => $data['riwayat_penyakit'] ?? null,
                'riwayat_alergi' => $data['riwayat_alergi'] ?? null,
                'diagnosa_medis' => $data['diagnosa_medis'],
                'tindakan_terapi' => $data['tindakan_terapi'] ?? null,
                'resep' => $data['resep'] ?? null,
                'admission_date' => $data['admission_date'] ?? null,
                'discharge_date' => $data['discharge_date'] ?? null,
                'catatan_perawatan' => $data['catatan_perawatan'] ?? null,
                'tekanan_darah' => $data['tekanan_darah'] ?? null,
                'nadi' => $data['nadi'] ?? null,
                'suhu' => $data['suhu'] ?? null,
                'respirasi' => $data['respirasi'] ?? null,
                'status' => 'final',
            ]);

            // Sync prescription items (if resep provided)
            $this->syncPrescriptionItems($asuhan, $data['resep'] ?? null);

            return redirect()->route('ashuhans.index', ['poli' => $poli])
                           ->with('success', 'Asuhan medis berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menyimpan asuhan: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Update discharge date for inpatient asuhan
     */
    public function updateDischarge(Request $request, Asuhan $asuhan)
    {
        if (strtolower($asuhan->poli_tujuan) !== 'rawat_inap') {
            return back()->withErrors(['error' => 'Tanggal pulang hanya dapat diupdate untuk asuhan rawat inap.']);
        }

        $data = $request->validate([
            'discharge_date' => 'required|date_format:Y-m-d\TH:i',
        ]);

        try {
            $asuhan->update([
                'discharge_date' => $data['discharge_date'],
            ]);

            return redirect()->route('ashuhans.show', $asuhan->id)
                           ->with('success', 'Tanggal pulang berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal memperbarui tanggal pulang: ' . $e->getMessage()]);
        }
    }

    /**
     * Clear discharge date for inpatient asuhan
     */
    public function clearDischarge(Request $request, Asuhan $asuhan)
    {
        if (strtolower($asuhan->poli_tujuan) !== 'rawat_inap') {
            return back()->withErrors(['error' => 'Tanggal pulang hanya dapat dihapus untuk asuhan rawat inap.']);
        }

        try {
            $asuhan->update([
                'discharge_date' => null,
            ]);

            return redirect()->route('ashuhans.show', $asuhan->id)
                           ->with('success', 'Tanggal pulang berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menghapus tanggal pulang: ' . $e->getMessage()]);
        }
    }
}
