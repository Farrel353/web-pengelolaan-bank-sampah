<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WasteDeposit;
use App\Models\Withdrawal;
use App\Models\Transaction;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Fitur 5 Pengurus: Melihat Laporan Transaksi (Setoran dan Penarikan)
     */
    public function index(Request $request)
    {
        $startDate = $request->query('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', now()->toDateString());
        $tab = $request->query('tab', 'semua'); // 'semua', 'setoran', 'penarikan'

        // Data Transaksi Setoran
        $depositsQuery = WasteDeposit::where('status', 'completed')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        $totalWeightKg = (clone $depositsQuery)->sum('total_weight_kg');
        $totalDanaSetoran = (clone $depositsQuery)->sum('total_amount');
        $totalTransaksiSetor = (clone $depositsQuery)->count();

        // Data Transaksi Penarikan
        $withdrawalsQuery = Withdrawal::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        $totalDanaPenarikan = (clone $withdrawalsQuery)->where('status', 'approved')->sum('amount');
        $totalTransaksiTarik = (clone $withdrawalsQuery)->where('status', 'approved')->count();

        // Mutasi Buku Besar (Semua Transaksi)
        $transactions = Transaction::with('user.nasabahProfile')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->latest()
            ->paginate(15);

        $deposits = (clone $depositsQuery)->with(['user.nasabahProfile', 'officer'])->latest()->paginate(15);
        $withdrawals = (clone $withdrawalsQuery)->with(['user.nasabahProfile', 'approver'])->latest()->paginate(15);

        return view('admin.laporan.index', compact(
            'startDate',
            'endDate',
            'tab',
            'totalWeightKg',
            'totalDanaSetoran',
            'totalTransaksiSetor',
            'totalDanaPenarikan',
            'totalTransaksiTarik',
            'transactions',
            'deposits',
            'withdrawals'
        ));
    }

    /**
     * Ekspor Laporan Transaksi ke File CSV
     */
    public function exportCsv(Request $request)
    {
        $startDate = $request->query('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', now()->toDateString());

        $transactions = Transaction::with('user.nasabahProfile')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->latest()
            ->get();

        $filename = "laporan-transaksi-bank-sampah-{$startDate}-sd-{$endDate}.csv";

        return new StreamedResponse(function () use ($transactions) {
            $handle = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($handle, [
                'ID Transaksi',
                'Tanggal & Waktu',
                'Nama Nasabah',
                'No. Rekening',
                'Tipe Transaksi',
                'Keterangan',
                'Nominal (Rp)',
                'Saldo Akhir (Rp)'
            ]);

            foreach ($transactions as $t) {
                fputcsv($handle, [
                    $t->id,
                    $t->created_at->format('Y-m-d H:i:s'),
                    $t->user->name ?? '-',
                    $t->user->nasabahProfile?->no_rekening ?? '-',
                    strtoupper($t->type === 'credit' ? 'SETORAN (KREDIT)' : 'PENARIKAN (DEBIT)'),
                    $t->description,
                    $t->amount,
                    $t->balance_after
                ]);
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
