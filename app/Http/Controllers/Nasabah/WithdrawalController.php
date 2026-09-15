<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Withdrawal;

class WithdrawalController extends Controller
{
    /**
     * Fitur 3 Nasabah: Melihat Riwayat Penarikan
     * Menampilkan: Tanggal Penarikan, Jumlah Saldo Ditarik, dan Status Penarikan
     */
    public function index()
    {
        $user = Auth::user();
        $withdrawals = Withdrawal::where('user_id', $user->id)->latest()->paginate(10);
        $profile = $user->nasabahProfile;

        return view('nasabah.tarik.index', compact('withdrawals', 'profile'));
    }

    public function create()
    {
        $user = Auth::user();
        $profile = $user->nasabahProfile;

        return view('nasabah.tarik.create', compact('user', 'profile'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $profile = $user->nasabahProfile;

        $validated = $request->validate([
            'channel' => 'required|string',
            'amount' => 'required|numeric|min:10000',
            'account_number' => 'required|string',
            'account_holder_name' => 'required|string',
        ]);

        $amount = (float) $validated['amount'];
        if ($profile->balance < $amount) {
            return back()->withErrors(['amount' => 'Saldo tabungan tidak mencukupi (Saldo Anda saat ini: Rp ' . number_format($profile->balance, 0, ',', '.') . ').'])->withInput();
        }

        $code = 'WD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        Withdrawal::create([
            'code' => $code,
            'user_id' => $user->id,
            'amount' => $amount,
            'points_used' => 0,
            'type' => 'bank_transfer',
            'channel' => $validated['channel'], // e.g. Bank BCA, Mandiri, BRI, GoPay, DANA, OVO
            'account_number' => $validated['account_number'],
            'account_holder_name' => $validated['account_holder_name'],
            'status' => 'pending',
            'admin_notes' => 'Menunggu verifikasi dan pencairan oleh pengurus.',
        ]);

        return redirect()->route('nasabah.tarik.index')->with('success', "Permintaan penarikan dana {$code} sebesar Rp " . number_format($amount, 0, ',', '.') . " berhasil diajukan!");
    }
}
