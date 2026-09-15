@extends('layouts.app')

@section('title', 'Fitur 5: Melihat Laporan Transaksi - Pengurus')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8" x-data="{ reportTab: '{{ $tab }}' }">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-800 mb-2">
                <i class="fa-solid fa-chart-column"></i> Fitur 5 Pengurus: Melihat Laporan Transaksi
            </span>
            <h1 class="text-2xl font-extrabold text-slate-900">Laporan Transaksi Setoran & Penarikan</h1>
            <p class="text-xs text-slate-500 mt-1">
                Pengurus dapat melihat data transaksi setoran dan penarikan untuk membantu pemantauan dan pengelolaan bank sampah.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.laporan.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-md transition flex items-center gap-2">
                <i class="fa-solid fa-file-csv text-emerald-400"></i> Ekspor Laporan CSV
            </a>
        </div>
    </div>

    <!-- Filter Rentang Tanggal -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
        <form action="{{ route('admin.laporan.index') }}" method="GET" class="flex flex-wrap items-end gap-4 text-xs">
            <div>
                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>

            <button type="submit" class="px-5 py-2 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold transition shadow-sm">
                <i class="fa-solid fa-filter mr-1"></i> Filter Periode
            </button>
        </form>
    </div>

    <!-- Ringkasan Statistik Pemantauan Operasional -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-sm">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Sampah Disetor</span>
            <p class="text-3xl font-extrabold text-slate-900 mt-1">
                {{ number_format($totalWeightKg, 1, ',', '.') }} <span class="text-xs text-slate-400 font-normal">kg</span>
            </p>
            <span class="text-[11px] text-emerald-600 font-semibold mt-2 block">
                {{ $totalTransaksiSetor }} kali penimbangan setoran
            </span>
        </div>

        <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-sm">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Nilai Setoran (Kas Keluar)</span>
            <p class="text-3xl font-extrabold text-emerald-600 mt-1">
                Rp {{ number_format($totalDanaSetoran, 0, ',', '.') }}
            </p>
            <span class="text-[11px] text-slate-400 mt-2 block">Penambahan saldo nasabah</span>
        </div>

        <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-sm">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Penarikan Diproses</span>
            <p class="text-3xl font-extrabold text-amber-600 mt-1">
                Rp {{ number_format($totalDanaPenarikan, 0, ',', '.') }}
            </p>
            <span class="text-[11px] text-slate-400 mt-2 block">{{ $totalTransaksiTarik }} kali pencairan dana</span>
        </div>

        <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-sm">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Saldo Bersih Masuk-Keluar</span>
            <p class="text-3xl font-extrabold text-purple-600 mt-1">
                Rp {{ number_format($totalDanaSetoran - $totalDanaPenarikan, 0, ',', '.') }}
            </p>
            <span class="text-[11px] text-slate-400 mt-2 block">Selisih mutasi kas periode ini</span>
        </div>
    </div>

    <!-- Tab Laporan: Semua Transaksi / Setoran / Penarikan -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="flex items-center gap-2 p-3 bg-slate-50 border-b border-slate-100 text-xs font-bold">
            <button type="button" @click="reportTab = 'semua'" class="px-4 py-2 rounded-xl transition" :class="reportTab === 'semua' ? 'bg-white text-purple-700 shadow-sm' : 'text-slate-600 hover:text-slate-900'">
                Buku Besar Semua Transaksi ({{ $transactions->total() }})
            </button>
            <button type="button" @click="reportTab = 'setoran'" class="px-4 py-2 rounded-xl transition" :class="reportTab === 'setoran' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-600 hover:text-slate-900'">
                Laporan Transaksi Setoran ({{ $deposits->total() }})
            </button>
            <button type="button" @click="reportTab = 'penarikan'" class="px-4 py-2 rounded-xl transition" :class="reportTab === 'penarikan' ? 'bg-white text-amber-700 shadow-sm' : 'text-slate-600 hover:text-slate-900'">
                Laporan Transaksi Penarikan ({{ $withdrawals->total() }})
            </button>
        </div>

        <!-- Tab 1: Semua Transaksi -->
        <div x-show="reportTab === 'semua'" class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-6">Waktu Transaksi</th>
                        <th class="py-3.5 px-6">Nama Nasabah</th>
                        <th class="py-3.5 px-6">No. Rekening</th>
                        <th class="py-3.5 px-6">Tipe Transaksi</th>
                        <th class="py-3.5 px-6">Keterangan</th>
                        <th class="py-3.5 px-6">Nominal (Rp)</th>
                        <th class="py-3.5 px-6 text-right">Saldo Akhir Nasabah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transactions as $t)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3.5 px-6 font-medium text-slate-700">{{ $t->created_at->format('d/m/Y H:i') }}</td>
                            <td class="py-3.5 px-6 font-bold text-slate-900">{{ $t->user->name ?? '-' }}</td>
                            <td class="py-3.5 px-6 font-mono text-slate-600">{{ $t->user->nasabahProfile?->no_rekening ?? '-' }}</td>
                            <td class="py-3.5 px-6">
                                <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $t->type === 'credit' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $t->type === 'credit' ? 'Setoran (Kredit)' : 'Penarikan (Debit)' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-6 text-slate-600 text-xs">{{ $t->description }}</td>
                            <td class="py-3.5 px-6 font-bold text-sm {{ $t->type === 'credit' ? 'text-emerald-600' : 'text-amber-700' }}">
                                {{ $t->type === 'credit' ? '+' : '-' }}Rp {{ number_format($t->amount, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-6 text-right font-mono font-bold text-slate-800">
                                Rp {{ number_format($t->balance_after, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">Belum ada transaksi pada periode terpilih.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4 border-t border-slate-100">
                {{ $transactions->links() }}
            </div>
        </div>

        <!-- Tab 2: Transaksi Setoran -->
        <div x-show="reportTab === 'setoran'" class="overflow-x-auto" x-cloak>
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-6">Kode Setor</th>
                        <th class="py-3.5 px-6">Tanggal</th>
                        <th class="py-3.5 px-6">Nasabah</th>
                        <th class="py-3.5 px-6">Berat Sampah</th>
                        <th class="py-3.5 px-6">Nilai Rupiah Hasil Konversi</th>
                        <th class="py-3.5 px-6">Petugas Penimbang</th>
                        <th class="py-3.5 px-6 text-right">Struk</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($deposits as $dep)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3.5 px-6 font-mono font-bold">{{ $dep->code }}</td>
                            <td class="py-3.5 px-6">{{ $dep->created_at->format('d/m/Y H:i') }}</td>
                            <td class="py-3.5 px-6 font-bold text-slate-800">{{ $dep->user->name ?? '-' }}</td>
                            <td class="py-3.5 px-6 font-bold text-slate-900">{{ $dep->total_weight_kg }} kg</td>
                            <td class="py-3.5 px-6 font-extrabold text-emerald-600">Rp {{ number_format($dep->total_amount, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-6 text-slate-500">{{ $dep->officer->name ?? 'Admin' }}</td>
                            <td class="py-3.5 px-6 text-right">
                                <a href="{{ route('admin.timbang.receipt', $dep->id) }}" class="text-emerald-600 hover:underline font-bold">Struk</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">Tidak ada data setoran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4 border-t border-slate-100">
                {{ $deposits->links() }}
            </div>
        </div>

        <!-- Tab 3: Transaksi Penarikan -->
        <div x-show="reportTab === 'penarikan'" class="overflow-x-auto" x-cloak>
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-6">Kode Pengajuan</th>
                        <th class="py-3.5 px-6">Tanggal</th>
                        <th class="py-3.5 px-6">Nasabah</th>
                        <th class="py-3.5 px-6">Tujuan Transfer</th>
                        <th class="py-3.5 px-6">Jumlah Ditarik</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6 text-right">Diproses Oleh</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($withdrawals as $w)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3.5 px-6 font-mono font-bold">{{ $w->code }}</td>
                            <td class="py-3.5 px-6">{{ $w->created_at->format('d/m/Y H:i') }}</td>
                            <td class="py-3.5 px-6 font-bold text-slate-800">{{ $w->user->name ?? '-' }}</td>
                            <td class="py-3.5 px-6 text-slate-600">{{ $w->channel }} ({{ $w->account_number }})</td>
                            <td class="py-3.5 px-6 font-extrabold text-amber-700">Rp {{ number_format($w->amount, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-6">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $w->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : ($w->status === 'rejected' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                                    {{ $w->status === 'approved' ? 'Selesai' : ($w->status === 'rejected' ? 'Ditolak' : 'Pending') }}
                                </span>
                            </td>
                            <td class="py-3.5 px-6 text-right text-slate-500">{{ $w->approver->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">Tidak ada data penarikan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4 border-t border-slate-100">
                {{ $withdrawals->links() }}
            </div>
        </div>

    </div>
</div>
@endsection
