<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index()
    {
        $medicines = Medicine::orderBy('name')->get();
        return view('pages.medicines.index', compact('medicines'));
    }

    public function create()
    {
        return view('pages.medicines.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'unit' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0',
        ]);

        Medicine::create($data);

        return redirect()->route('medicines.index')->with('success', 'Obat berhasil ditambahkan.');
    }

    public function edit(Medicine $medicine)
    {
        return view('pages.medicines.edit', compact('medicine'));
    }

    public function update(Request $request, Medicine $medicine)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'unit' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0',
        ]);

        $medicine->update($data);

        return redirect()->route('medicines.index')->with('success', 'Obat berhasil diperbarui.');
    }

    public function destroy(Medicine $medicine)
    {
        $medicine->delete();
        return redirect()->route('medicines.index')->with('success', 'Obat berhasil dihapus.');
    }
}
