<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WasteDeposit;
use App\Models\WasteCategory;

class DepositController extends Controller
{
    /**
     * Fitur 2 Nasabah: Melihat Riwayat Setoran
     * Menampilkan: Tanggal Setoran, Jenis Sampah, Berat Sampah, Nilai Rupiah Konversi
     */
    public function index()
    {
        $user = Auth::user();
        $deposits = WasteDeposit::with(['details.item.category', 'officer'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('nasabah.setor.index', compact('deposits'));
    }

    public function create()
    {
        $categories = WasteCategory::with('items')->where('is_active', true)->get();
        $user = Auth::user();

        return view('nasabah.setor.create', compact('categories', 'user'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'deposit_type' => 'required|in:pickup,drop_off',
            'pickup_date' => 'nullable|date|after_or_equal:today',
            'pickup_time_slot' => 'nullable|string',
            'pickup_address' => 'nullable|string',
            'estimated_weight_kg' => 'nullable|numeric|min:0.1',
            'notes' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $code = 'DEP-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        $deposit = WasteDeposit::create([
            'code' => $code,
            'user_id' => $user->id,
            'deposit_type' => $validated['deposit_type'],
            'status' => 'pending', // Menunggu penimbangan pengurus
            'pickup_address' => $validated['deposit_type'] === 'pickup' ? ($validated['pickup_address'] ?? $user->nasabahProfile?->address) : null,
            'pickup_date' => $validated['pickup_date'] ?? now()->toDateString(),
            'pickup_time_slot' => $validated['pickup_time_slot'] ?? 'Pagi (08:00 - 11:00)',
            'total_weight_kg' => $validated['estimated_weight_kg'] ?? 0,
            'total_amount' => 0,
            'total_points' => 0,
            'total_co2_kg' => 0,
            'notes' => $validated['notes'] ?? 'Pengajuan setor sampah oleh nasabah.',
        ]);

        return redirect()->route('nasabah.setor.index')->with('success', "Pengajuan setor sampah {$deposit->code} berhasil dibuat! Menunggu penimbangan pengurus.");
    }

    public function show($id)
    {
        $user = Auth::user();
        $deposit = WasteDeposit::with(['details.item.category', 'officer', 'user.nasabahProfile'])
            ->where('user_id', $user->id)
            ->findOrFail($id);

        return view('nasabah.setor.show', compact('deposit'));
    }
}
