<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\NasabahProfile;
use App\Models\WasteDeposit;
use App\Models\Withdrawal;
use App\Models\Transaction;
use Illuminate\Support\Str;

class NasabahController extends Controller
{
    /**
     * Fitur 1 Pengurus: Mengelola Data Nasabah (Melihat Daftar Nasabah)
     */
    public function index(Request $request)
    {
        $search = $request->query('cari');

        $query = User::where('role', 'nasabah')->with('nasabahProfile');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('email', 'ilike', "%{$search}%")
                  ->orWhere('phone', 'ilike', "%{$search}%")
                  ->orWhereHas('nasabahProfile', function($pq) use ($search) {
                      $pq->where('no_rekening', 'ilike', "%{$search}%");
                  });
            });
        }

        $nasabahs = $query->orderBy('name')->paginate(12);

        return view('admin.nasabah.index', compact('nasabahs', 'search'));
    }

    /**
     * Menambah Data Nasabah Baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'rt_rw' => 'nullable|string',
            'initial_balance' => 'nullable|numeric|min:0',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => 'nasabah',
            'password' => Hash::make('password123'),
        ]);

        $year = date('Y');
        $randomNum = str_pad(User::count(), 4, '0', STR_PAD_LEFT);
        $noRekening = "BS-{$year}-{$randomNum}";

        NasabahProfile::create([
            'user_id' => $user->id,
            'no_rekening' => $noRekening,
            'balance' => $validated['initial_balance'] ?? 0,
            'points' => 0,
            'co2_saved_kg' => 0,
            'address' => $validated['address'],
            'rt_rw' => $validated['rt_rw'] ?? null,
            'qr_code_token' => 'QR-' . strtoupper(Str::slug($user->name)) . '-' . $randomNum,
            'status' => 'active',
        ]);

        return redirect()->route('admin.nasabah.index')->with('success', "Nasabah '{$user->name}' berhasil ditambahkan dengan No. Rekening {$noRekening}.");
    }

    /**
     * Mengubah Data Nasabah (Edit)
     */
    public function update(Request $request, $id)
    {
        $user = User::where('role', 'nasabah')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'rt_rw' => 'nullable|string',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ]);

        $profile = $user->nasabahProfile;
        if ($profile) {
            $profile->update([
                'address' => $validated['address'],
                'rt_rw' => $validated['rt_rw'] ?? null,
            ]);
        }

        return redirect()->route('admin.nasabah.index')->with('success', "Data nasabah '{$user->name}' berhasil diperbarui.");
    }

    /**
     * Menghapus Data Nasabah
     */
    public function destroy($id)
    {
        $user = User::where('role', 'nasabah')->findOrFail($id);
        $name = $user->name;

        // Cascade delete relations and user
        $user->delete();

        return redirect()->route('admin.nasabah.index')->with('success', "Nasabah '{$name}' berhasil dihapus dari sistem.");
    }

    /**
     * Melihat Data Nasabah serta Riwayat Transaksi Nasabah
     */
    public function show($id)
    {
        $nasabah = User::with('nasabahProfile')->where('role', 'nasabah')->findOrFail($id);
        
        // Riwayat Setoran Nasabah
        $deposits = WasteDeposit::with('details.item')
            ->where('user_id', $nasabah->id)
            ->latest()
            ->get();

        // Riwayat Penarikan Saldo Nasabah
        $withdrawals = Withdrawal::where('user_id', $nasabah->id)
            ->latest()
            ->get();

        // Riwayat Mutasi Buku Rekening (Debit & Kredit)
        $transactions = Transaction::where('user_id', $nasabah->id)
            ->latest()
            ->get();

        return view('admin.nasabah.show', compact('nasabah', 'deposits', 'withdrawals', 'transactions'));
    }
}
