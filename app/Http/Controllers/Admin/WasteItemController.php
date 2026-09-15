<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WasteCategory;
use App\Models\WasteItem;
use Illuminate\Support\Str;

class WasteItemController extends Controller
{
    /**
     * Fitur 2 Pengurus: Mengelola Jenis dan Harga Sampah
     */
    public function index()
    {
        $categories = WasteCategory::with('items')->get();
        $items = WasteItem::with('category')->orderBy('waste_category_id')->orderBy('name')->get();

        return view('admin.sampah.index', compact('categories', 'items'));
    }

    /**
     * Menambah Jenis Sampah Baru & Menentukan Harga per kg
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'waste_category_id' => 'required|exists:waste_categories,id',
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:10',
            'buy_price_per_unit' => 'required|numeric|min:0',
            'points_per_unit' => 'nullable|integer|min:0',
            'co2_factor_per_unit' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $validated['points_per_unit'] = $validated['points_per_unit'] ?? 10;
        $validated['co2_factor_per_unit'] = $validated['co2_factor_per_unit'] ?? 1.5;
        $validated['is_active'] = true;

        WasteItem::create($validated);

        return redirect()->route('admin.sampah.index')->with('success', "Jenis sampah '{$validated['name']}' berhasil ditambahkan.");
    }

    /**
     * Mengubah Jenis Sampah & Menentukan Harga Baru per kg
     */
    public function update(Request $request, $id)
    {
        $item = WasteItem::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'buy_price_per_unit' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $item->update($validated);

        return redirect()->route('admin.sampah.index')->with('success', "Harga dan data '{$item->name}' berhasil diperbarui menjadi Rp " . number_format($validated['buy_price_per_unit'], 0, ',', '.') . "/{$item->unit}.");
    }

    /**
     * Menghapus Jenis Sampah
     */
    public function destroy($id)
    {
        $item = WasteItem::findOrFail($id);
        $name = $item->name;
        $item->delete();

        return redirect()->route('admin.sampah.index')->with('success', "Jenis sampah '{$name}' berhasil dihapus.");
    }

    /**
     * Toggle Status Aktif / Nonaktif
     */
    public function toggle($id)
    {
        $item = WasteItem::findOrFail($id);
        $item->is_active = !$item->is_active;
        $item->save();

        $status = $item->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('admin.sampah.index')->with('success', "Status '{$item->name}' berhasil {$status}.");
    }

    /**
     * Menambah Kategori Baru
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = true;

        WasteCategory::create($validated);

        return redirect()->route('admin.sampah.index')->with('success', "Kategori sampah '{$validated['name']}' berhasil dibuat.");
    }
}
