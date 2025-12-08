<?php

namespace App\Http\Controllers;

use App\Models\Asuhan;
use App\Models\Medicine;
use App\Models\PrescriptionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FarmasiController extends Controller
{
    // List pending prescriptions
    public function index(Request $request)
    {
        $q = $request->query('q');

        // Show all Asuhan (so farmasi can input obat from here) but exclude
        // rawat_inap that already have a discharge_date (those who went home).
        // Eager-load prescriptionItems for display and editing.
        $query = Asuhan::with(['patient', 'user', 'prescriptionItems'])
            // only show prescriptions that have not been collected yet
            ->whereNull('resep_collected_at')
            ->where(function ($q) {
                $q->whereRaw("LOWER(poli_tujuan) != 'rawat_inap'")
                    ->orWhereNull('discharge_date');
            });

        if ($q) {
            $query->whereHas('patient', function ($p) use ($q) {
                $p->where('nama_pasien', 'like', "%{$q}%")
                    ->orWhere('no_rm', 'like', "%{$q}%");
            });
        }

        $prescriptions = $query->orderBy('tanggal_asuhan', 'desc')->paginate(15)->withQueryString();

        // also provide medicines list for the dispense UI
        $medicines = Medicine::orderBy('name')->get();

        return view('pages.farmasi.prescriptions', compact('prescriptions', 'q', 'medicines'));
    }

    // Mark prescription as collected
    public function collect(Request $request, Asuhan $asuhan)
    {
        $asuhan->resep_collected_at = now();
        $asuhan->save();

        // If this is an AJAX request, return JSON so frontend can update without reload
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Resep pasien telah ditandai sebagai sudah diambil.',
                'id' => $asuhan->id,
            ]);
        }

        return back()->with('success', 'Resep pasien telah ditandai sebagai sudah diambil.');
    }

    // Mark the queue item as finished (end of this registration period)
    public function finish(Request $request, Asuhan $asuhan)
    {
        // Mark as collected and final
        $asuhan->resep_collected_at = now();
        $asuhan->status = 'final';
        $asuhan->save();

        return back()->with('success', 'Antrian pasien telah diselesaikan.');
    }

    // Finish all pending prescriptions (bulk)
    public function finishAll(Request $request)
    {
        // Only finish the Asuhan items specified by ids[] (current page selection)
        $ids = (array) $request->input('ids', []);

        if (empty($ids)) {
            return back()->with('success', 'Tidak ada antrian pada halaman ini yang perlu diselesaikan.');
        }

        $now = now();
        // allow finishing items even if they don't have a 'resep' value
        \App\Models\Asuhan::whereIn('id', $ids)
            ->whereNull('resep_collected_at')
            ->update([
                'resep_collected_at' => $now,
                'status' => 'final',
                'updated_at' => $now,
            ]);

        return back()->with('success', 'Antrian resep pada halaman ini telah diselesaikan.');
    }

    // Dispense prescription: accept items (array) and total price
    public function dispense(Request $request, Asuhan $asuhan)
    {
        // DEBUG: Log raw input
        Log::info('Raw items input:', ['items' => $request->input('items')]);

        // Filter out empty rows from items array
        $rawItems = $request->input('items', []);
        $filtered = [];

        if (is_array($rawItems)) {
            foreach ($rawItems as $index => $it) {
                if (!is_array($it)) {
                    Log::info("Item $index is not array", ['item' => $it]);
                    continue;
                }

                // Check if row has meaningful data
                $hasName = isset($it['name']) && is_string($it['name']) && trim($it['name']) !== '';
                $hasId = isset($it['id']) && trim($it['id']) !== '' && trim($it['id']) !== '0';

                Log::info("Item $index check", [
                    'item' => $it,
                    'hasName' => $hasName,
                    'hasId' => $hasId
                ]);

                // Only include rows that have either name or medicine id
                if ($hasName || $hasId) {
                    // Ensure name is filled - get from medicine if not provided
                    if (!$hasName && $hasId) {
                        $medicine = Medicine::find($it['id']);
                        if ($medicine) {
                            $it['name'] = $medicine->name;
                        }
                    }

                    // Ensure qty has a value
                    if (!isset($it['qty']) || trim($it['qty']) === '') {
                        $it['qty'] = 1;
                    }

                    $filtered[] = $it;
                }
            }
        }

        Log::info('Filtered items:', ['filtered' => $filtered]);

        // If no valid items, return with message
        if (empty($filtered)) {
            return back()->with('error', 'Tidak ada obat yang diinput. Silakan tambahkan minimal 1 obat.');
        }

        // Overwrite request items with filtered ones
        $request->merge(['items' => $filtered]);

        // Validate only the filtered items
        $data = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|integer|exists:medicines,id',
            'items.*.name' => 'required|string',
            'items.*.unit' => 'nullable|string',
            'items.*.price' => 'nullable|numeric',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.note' => 'nullable|string',
            'total' => 'nullable|numeric|min:0',
        ], [
            'items.required' => 'Minimal harus ada 1 obat yang diinput.',
            'items.*.name.required' => 'Nama obat wajib diisi.',
            'items.*.qty.required' => 'Jumlah obat wajib diisi.',
            'items.*.qty.min' => 'Jumlah obat minimal 1.',
        ]);

        // Remove existing prescription items (we will recreate)
        PrescriptionItem::where('asuhan_id', $asuhan->id)->delete();

        $calculatedTotal = 0;

        foreach ($data['items'] as $it) {
            try {
                // Guard medicine_id against foreign key constraint
                $medicineId = null;
                if (!empty($it['id']) && is_numeric($it['id'])) {
                    $exists = Medicine::where('id', intval($it['id']))->exists();
                    if ($exists) {
                        $medicineId = intval($it['id']);
                    }
                }

                $price = isset($it['price']) ? floatval($it['price']) : 0;
                $qty = isset($it['qty']) ? intval($it['qty']) : 1;

                PrescriptionItem::create([
                    'asuhan_id' => $asuhan->id,
                    'medicine_id' => $medicineId,
                    'name' => $it['name'],
                    'unit' => $it['unit'] ?? null,
                    'price' => $price,
                    'qty' => $qty,
                    'note' => $it['note'] ?? null,
                ]);

                // Calculate total
                $calculatedTotal += ($price * $qty);
            } catch (\Throwable $e) {
                Log::error('Failed to create PrescriptionItem', [
                    'error' => $e->getMessage(),
                    'item' => $it,
                    'asuhan_id' => $asuhan->id
                ]);
                return back()->with('error', 'Terjadi kesalahan saat menyimpan item resep: ' . $e->getMessage());
            }
        }

        // Set collected timestamp and calculated total price
        $asuhan->resep_collected_at = now();
        $asuhan->resep_total = $calculatedTotal;
        $asuhan->save();

        return back()->with('success', 'Resep berhasil diinput dan diselesaikan. Total: Rp ' . number_format($calculatedTotal, 0, ',', '.'));
    }
}