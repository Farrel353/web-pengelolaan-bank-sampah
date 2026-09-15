<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Withdrawal;
use App\Models\Transaction;

class WithdrawalApprovalController extends Controller
{
    /**
     * Fitur 4 Pengurus: Memproses Penarikan Saldo
     * Pengurus dapat melihat daftar permintaan penarikan saldo nasabah
     */
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');

        $query = Withdrawal::with(['user.nasabahProfile', 'approver']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $withdrawals = $query->latest()->paginate(15);
        $pendingCount = Withdrawal::where('status', 'pending')->count();

        return view('admin.penarikan.index', compact('withdrawals', 'status', 'pendingCount'));
    }

    /**
     * Memproses Penarikan dan Mengurangi Saldo Nasabah Sesuai Jumlah yang Ditarik
     */
    public function approve(Request $request, $id)
    {
        $withdrawal = Withdrawal::with('user.nasabahProfile')->findOrFail($id);

        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Permohonan penarikan ini sudah diproses sebelumnya.');
        }

        $user = $withdrawal->user;
        $profile = $user->nasabahProfile;

        if ($profile->balance < $withdrawal->amount) {
            return back()->with('error', "Saldo nasabah tidak mencukupi untuk penarikan ini (Saldo: Rp " . number_format($profile->balance, 0, ',', '.') . ").");
        }

        DB::beginTransaction();
        try {
            // Kurangi saldo nasabah sesuai jumlah yang ditarik
            $profile->balance -= $withdrawal->amount;
            $profile->save();

            // Catat transaksi mutasi debit pengurangan saldo
            Transaction::create([
                'user_id' => $user->id,
                'reference_type' => 'withdrawal',
                'reference_id' => $withdrawal->id,
                'type' => 'debit',
                'amount' => $withdrawal->amount,
                'points' => 0,
                'balance_after' => $profile->balance,
                'points_after' => $profile->points,
                'description' => "Penarikan Saldo {$withdrawal->code} ke {$withdrawal->channel} ({$withdrawal->account_number})",
            ]);

            // Update status penarikan menjadi disetujui / diproses
            $withdrawal->status = 'approved';
            $withdrawal->approved_by = Auth::id();
            $withdrawal->approved_at = now();
            $withdrawal->admin_notes = $request->input('admin_notes', 'Penarikan saldo telah berhasil diproses dan ditransfer oleh pengurus.');
            $withdrawal->save();

            DB::commit();

            return redirect()->route('admin.penarikan.index')->with('success', "Penarikan dana {$withdrawal->code} sebesar Rp " . number_format($withdrawal->amount, 0, ',', '.') . " berhasil diproses dan saldo nasabah telah dikurangi.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses penarikan: ' . $e->getMessage());
        }
    }

    /**
     * Menolak Permintaan Penarikan
     */
    public function reject(Request $request, $id)
    {
        $withdrawal = Withdrawal::findOrFail($id);

        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Permintaan penarikan ini sudah diproses sebelumnya.');
        }

        $withdrawal->status = 'rejected';
        $withdrawal->approved_by = Auth::id();
        $withdrawal->approved_at = now();
        $withdrawal->admin_notes = $request->input('admin_notes', 'Ditolak: data nomor rekening/tujuan tidak sesuai.');
        $withdrawal->save();

        return redirect()->route('admin.penarikan.index')->with('success', "Permintaan penarikan {$withdrawal->code} telah ditolak.");
    }
}
