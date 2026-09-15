<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WasteDeposit;
use App\Models\Withdrawal;
use App\Models\WasteCategory;
use App\Models\WasteItem;
use App\Models\NasabahProfile;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Metrik Utama
        $totalSampahKg = WasteDeposit::where('status', 'completed')->sum('total_weight_kg');
        $totalNasabah = User::where('role', 'nasabah')->count();
        $totalKasKeluar = WasteDeposit::where('status', 'completed')->sum('total_amount');
        $pendingWithdrawalsCount = Withdrawal::where('status', 'pending')->count();
        $pendingDepositsCount = WasteDeposit::where('status', 'pending')->count();
        $totalCo2Kg = WasteDeposit::where('status', 'completed')->sum('total_co2_kg');

        // 5 Setoran Terakhir
        $recentDeposits = WasteDeposit::with(['user', 'officer'])->latest()->take(6)->get();

        // 5 Penarikan Pending Terakhir
        $pendingWithdrawals = Withdrawal::with('user')->where('status', 'pending')->latest()->take(5)->get();

        // Leaderboard Nasabah Teraktif
        $topNasabah = NasabahProfile::with('user')
            ->orderBy('points', 'desc')
            ->take(5)
            ->get();

        // Komposisi Kategori Sampah
        $categoriesStats = WasteCategory::withCount('items')->get();

        return view('admin.dashboard', compact(
            'totalSampahKg',
            'totalNasabah',
            'totalKasKeluar',
            'pendingWithdrawalsCount',
            'pendingDepositsCount',
            'totalCo2Kg',
            'recentDeposits',
            'pendingWithdrawals',
            'topNasabah',
            'categoriesStats'
        ));
    }
}
