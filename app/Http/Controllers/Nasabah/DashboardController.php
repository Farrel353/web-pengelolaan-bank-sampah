<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WasteDeposit;
use App\Models\Withdrawal;
use App\Models\WasteItem;
use App\Models\WasteCategory;

class DashboardController extends Controller
{
    /**
     * Fitur 1 Nasabah: Melihat Saldo & Ringkasan Transaksi
     */
    public function index()
    {
        $user = Auth::user();
        $profile = $user->nasabahProfile;

        // Total setoran yang telah diproses
        $totalSetorRp = WasteDeposit::where('user_id', $user->id)
            ->where('status', 'completed')
            ->sum('total_amount');

        $totalSetorKg = WasteDeposit::where('user_id', $user->id)
            ->where('status', 'completed')
            ->sum('total_weight_kg');

        // Total penarikan yang telah diproses
        $totalTarikRp = Withdrawal::where('user_id', $user->id)
            ->where('status', 'approved')
            ->sum('amount');

        // 5 Setoran Terakhir
        $recentDeposits = WasteDeposit::with('details.item')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // 5 Penarikan Terakhir
        $recentWithdrawals = Withdrawal::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // Sampel harga sampah terkini
        $activePrices = WasteItem::with('category')
            ->where('is_active', true)
            ->orderBy('name')
            ->take(6)
            ->get();

        return view('nasabah.dashboard', compact(
            'user',
            'profile',
            'totalSetorRp',
            'totalSetorKg',
            'totalTarikRp',
            'recentDeposits',
            'recentWithdrawals',
            'activePrices'
        ));
    }

    /**
     * Fitur 4 Nasabah: Melihat Harga Sampah
     */
    public function harga(Request $request)
    {
        $selectedCategory = $request->query('kategori');
        $search = $request->query('cari');

        $categories = WasteCategory::where('is_active', true)->get();

        $query = WasteItem::with('category')->where('is_active', true);

        if ($selectedCategory) {
            $query->whereHas('category', function($q) use ($selectedCategory) {
                $q->where('slug', $selectedCategory);
            });
        }

        if ($search) {
            $query->where('name', 'ilike', "%{$search}%");
        }

        $items = $query->orderBy('name')->get();

        return view('nasabah.harga', compact('categories', 'items', 'selectedCategory', 'search'));
    }

    /**
     * Kartu Identitas Digital Nasabah
     */
    public function kartu()
    {
        $user = Auth::user();
        $profile = $user->nasabahProfile;

        return view('nasabah.kartu', compact('user', 'profile'));
    }
}
