<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\WasteItem;
use App\Models\WasteDeposit;
use App\Models\WasteDepositDetail;
use App\Models\Transaction;
use App\Models\NasabahProfile;

class TimbangSampahController extends Controller
{
    /**
     * Fitur 3 Pengurus: Mencatat Setoran Sampah
     */
    public function index()
    {
        $nasabahs = User::where('role', 'nasabah')
            ->with('nasabahProfile')
            ->orderBy('name')
            ->get();

        $items = WasteItem::with('category')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $pendingDeposits = WasteDeposit::with('user')
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('admin.timbang.index', compact('nasabahs', 'items', 'pendingDeposits'));
    }

    /**
     * Simpan Pencatatan Setoran Sampah:
     * Menghitung nilai setoran = berat sampah * harga/kg,
     * kemudian otomatis menambahkan hasilnya ke saldo nasabah.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'deposit_type' => 'required|in:drop_off,pickup',
            'items' => 'required|array|min:1',
            'items.*.waste_item_id' => 'required|exists:waste_items,id',
            'items.*.weight' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string|max:500',
            'pending_deposit_id' => 'nullable|exists:waste_deposits,id',
        ]);

        $officer = Auth::user();
        $user = User::with('nasabahProfile')->findOrFail($validated['user_id']);

        DB::beginTransaction();
        try {
            $totalWeight = 0;
            $totalAmount = 0;
            $totalPoints = 0;
            $totalCo2 = 0;

            $depositCode = 'DEP-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

            if (!empty($validated['pending_deposit_id'])) {
                $deposit = WasteDeposit::findOrFail($validated['pending_deposit_id']);
                $deposit->officer_id = $officer->id;
                $deposit->status = 'completed';
            } else {
                $deposit = new WasteDeposit();
                $deposit->code = $depositCode;
                $deposit->user_id = $user->id;
                $deposit->officer_id = $officer->id;
                $deposit->deposit_type = $validated['deposit_type'];
                $deposit->status = 'completed';
            }

            $deposit->notes = $validated['notes'] ?? 'Pencatatan setoran sampah di Bank Sampah.';
            $deposit->save();

            foreach ($validated['items'] as $line) {
                $wasteItem = WasteItem::findOrFail($line['waste_item_id']);
                $weight = (float) $line['weight'];
                $price = (float) $wasteItem->buy_price_per_unit;
                $pointsUnit = (int) $wasteItem->points_per_unit;
                $co2Unit = (float) $wasteItem->co2_factor_per_unit;

                // Hitung nilai setoran berdasarkan berat * harga per kilogram
                $subtotalAmount = $weight * $price;
                $subtotalPoints = (int) round($weight * $pointsUnit);
                $subtotalCo2 = round($weight * $co2Unit, 2);

                $totalWeight += $weight;
                $totalAmount += $subtotalAmount;
                $totalPoints += $subtotalPoints;
                $totalCo2 += $subtotalCo2;

                WasteDepositDetail::create([
                    'waste_deposit_id' => $deposit->id,
                    'waste_item_id' => $wasteItem->id,
                    'weight' => $weight,
                    'price_per_unit' => $price,
                    'points_per_unit' => $pointsUnit,
                    'subtotal_amount' => $subtotalAmount,
                    'subtotal_points' => $subtotalPoints,
                    'subtotal_co2' => $subtotalCo2,
                ]);
            }

            $deposit->total_weight_kg = $totalWeight;
            $deposit->total_amount = $totalAmount;
            $deposit->total_points = $totalPoints;
            $deposit->total_co2_kg = $totalCo2;
            $deposit->save();

            // Tambahkan hasilnya ke saldo nasabah
            $profile = $user->nasabahProfile;
            if (!$profile) {
                $profile = NasabahProfile::create([
                    'user_id' => $user->id,
                    'no_rekening' => 'BS-' . date('Y') . '-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                    'balance' => 0,
                    'points' => 0,
                    'co2_saved_kg' => 0,
                ]);
            }

            $profile->balance += $totalAmount;
            $profile->points += $totalPoints;
            $profile->co2_saved_kg += $totalCo2;
            $profile->save();

            // Catat mutasi penambahan saldo (kredit)
            Transaction::create([
                'user_id' => $user->id,
                'reference_type' => 'deposit',
                'reference_id' => $deposit->id,
                'type' => 'credit',
                'amount' => $totalAmount,
                'points' => $totalPoints,
                'balance_after' => $profile->balance,
                'points_after' => $profile->points,
                'description' => "Setoran Sampah {$deposit->code} ({$totalWeight} kg)",
            ]);

            DB::commit();

            return redirect()->route('admin.timbang.receipt', $deposit->id)
                ->with('success', "Setoran berhasil dicatat! Saldo Rp " . number_format($totalAmount, 0, ',', '.') . " telah otomatis ditambahkan ke nasabah {$user->name}.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses setoran: ' . $e->getMessage())->withInput();
        }
    }

    public function receipt($id)
    {
        $deposit = WasteDeposit::with(['user.nasabahProfile', 'officer', 'details.item.category'])->findOrFail($id);

        return view('admin.timbang.receipt', compact('deposit'));
    }
}
