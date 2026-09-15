@extends('layouts.app')

@section('title', 'Data & Riwayat Transaksi Nasabah - ' . $nasabah->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8" x-data="{ tab: 'setoran' }">
    <!-- Back button & Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.nasabah.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 flex items-center gap-1 mb-1">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Pengelolaan Data Nasabah
            </a>
            <h1 class="text-2xl font-extrabold text-slate-900">Data & Riwayat Transaksi Nasabah</h1>
            <p class="text-xs text-slate-500">Melihat detail profil nasabah serta riwayat transaksi setoran dan penarikan yang pernah dilakukan.</p>
        </div>
        <a href="{{ route('admin.timbang.index') }}" class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs shadow-md shadow-amber-500/20 transition flex items-center gap-1.5">
            <i class="fa-solid fa-scale-balanced"></i> + Catat Setoran untuk Nasabah Ini
        </a>
    </div>

    <!-- Nasabah Profile Summary Card -->
    <div class="p-6 sm:p-8 rounded-3xl bg-white border border-slate-200 shadow-sm grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-800 font-black text-xl flex items-center justify-center">
                {{ strtoupper(substr($nasabah->name, 0, 2)) }}
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-900">{{ $nasabah->name }}</h2>
                <p class="font-mono text-xs text-emerald-700 font-bold">{{ $nasabah->nasabahProfile?->no_rekening ?? '-' }}</p>
                <p class="text-xs text-slate-500 mt-1">
                    <i class="fa-solid fa-phone text-slate-400 mr-1"></i> {{ $nasabah->phone }} &bull; {{ $nasabah->email }}
                </p>
                <p class="text-[11px] text-slate-400 mt-0.5">
                    <i class="fa-solid fa-location-dot text-slate-400 mr-1"></i> {{ $nasabah->nasabahProfile?->address ?? '-' }} {{ $nasabah->nasabahProfile?->rt_rw ? '(RT ' . $nasabah->nasabahProfile->rt_rw . ')' : '' }}
                </p>
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200">
            <span class="text-[10px] uppercase tracking-wider text-emerald-700 font-bold block">Saldo Nasabah Saat Ini</span>
            <p class="text-3xl font-black text-emerald-800 mt-1">
                Rp {{ number_format($nasabah->nasabahProfile?->balance ?? 0, 0, ',', '.') }}
            </p>
            <span class="text-[11px] text-emerald-600">Berdasarkan total setoran & penarikan</span>
        </div>

        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-600 space-y-1.5">
            <div class="flex justify-between">
                <span>Total Setoran:</span>
                <strong class="text-slate-800">{{ $deposits->count() }} kali ({{ $deposits->sum('total_weight_kg') }} kg)</strong>
            </div>
            <div class="flex justify-between">
                <span>Total Penarikan:</span>
                <strong class="text-slate-800">{{ $withdrawals->count() }} kali (Rp {{ number_format($withdrawals->where('status', 'approved')->sum('amount'), 0, ',', '.') }})</strong>
            </div>
            <div class="flex justify-between">
                <span>Status Akun:</span>
                <span class="font-bold text-emerald-600">Aktif</span>
            </div>
        </div>
    </div>

    <!-- Riwayat Transaksi Nasabah (Setoran & Penarikan) -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="flex items-center gap-2 p-3 bg-slate-50 border-b border-slate-100 text-xs font-bold">
            <button type="button" @click="tab = 'setoran'" class="px-4 py-2 rounded-xl transition" :class="tab === 'setoran' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-600 hover:text-slate-900'">
                Riwayat Setoran Sampah ({{ $deposits->count() }})
            </button>
            <button type="button" @click="tab = 'penarikan'" class="px-4 py-2 rounded-xl transition" :class="tab === 'penarikan' ? 'bg-white text-amber-700 shadow-sm' : 'text-slate-600 hover:text-slate-900'">
                Riwayat Penarikan Saldo ({{ $withdrawals->count() }})
            </button>
            <button type="button" @click="tab = 'mutasi'" class="px-4 py-2 rounded-xl transition" :class="tab === 'mutasi' ? 'bg-white text-blue-700 shadow-sm' : 'text-slate-600 hover:text-slate-900'">
                Buku Mutasi Debit/Kredit ({{ $transactions->count() }})
            </button>
        </div>

        <!-- Tab 1: Riwayat Setoran -->
        <div x-show="tab === 'setoran'" class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-6">Tanggal Setor</th>
                        <th class="py-3.5 px-6">Kode Transaksi</th>
                        <th class="py-3.5 px-6">Jenis Sampah</th>
                        <th class="py-3.5 px-6">Berat Sampah</th>
                        <th class="py-3.5 px-6">Nilai Rupiah Hasil Konversi</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($deposits as $d)
                        <tr class="hover:bg-slate-50">
                            <td class="py-3.5 px-6">{{ $d->created_at->format('d/m/Y H:i') }}</td>
                            <td class="py-3.5 px-6 font-mono font-bold">{{ $d->code }}</td>
                            <td class="py-3.5 px-6">
                                @foreach($d->details as $det)
                                    <div>{{ $det->item->name ?? 'Sampah' }} ({{ $det->weight }} {{ $det->item->unit ?? 'kg' }})</div>
                                @endforeach
                            </td>
                            <td class="py-3.5 px-6 font-bold">{{ $d->total_weight_kg }} kg</td>
                            <td class="py-3.5 px-6 font-extrabold text-emerald-600">Rp {{ number_format($d->total_amount, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-6">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $d->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $d->status === 'completed' ? 'Selesai' : 'Pending' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-6 text-right">
                                <a href="{{ route('admin.timbang.receipt', $d->id) }}" class="text-emerald-600 font-bold hover:underline">
                                    Struk
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">Belum ada riwayat setoran untuk nasabah ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Tab 2: Riwayat Penarikan -->
        <div x-show="tab === 'penarikan'" class="overflow-x-auto" x-cloak>
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-6">Tanggal Permintaan</th>
                        <th class="py-3.5 px-6">Kode Pengajuan</th>
                        <th class="py-3.5 px-6">Tujuan Transfer</th>
                        <th class="py-3.5 px-6">Jumlah Saldo Ditarik</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($withdrawals as $w)
                        <tr class="hover:bg-slate-50">
                            <td class="py-3.5 px-6">{{ $w->created_at->format('d/m/Y H:i') }}</td>
                            <td class="py-3.5 px-6 font-mono font-bold">{{ $w->code }}</td>
                            <td class="py-3.5 px-6">{{ $w->channel }} ({{ $w->account_number }})</td>
                            <td class="py-3.5 px-6 font-extrabold text-amber-700">Rp {{ number_format($w->amount, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-6">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $w->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : ($w->status === 'rejected' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                                    {{ $w->status === 'approved' ? 'Selesai' : ($w->status === 'rejected' ? 'Ditolak' : 'Pending') }}
                                </span>
                            </td>
                            <td class="py-3.5 px-6 text-slate-500 text-[11px]">{{ $w->admin_notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">Belum ada riwayat penarikan untuk nasabah ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Tab 3: Buku Mutasi Debit/Kredit -->
        <div x-show="tab === 'mutasi'" class="overflow-x-auto" x-cloak>
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-6">Waktu Transaksi</th>
                        <th class="py-3.5 px-6">Keterangan Transaksi</th>
                        <th class="py-3.5 px-6">Tipe</th>
                        <th class="py-3.5 px-6">Nominal (Rp)</th>
                        <th class="py-3.5 px-6 text-right">Saldo Akhir Nasabah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transactions as $t)
                        <tr class="hover:bg-slate-50">
                            <td class="py-3.5 px-6 font-medium text-slate-700">{{ $t->created_at->format('d/m/Y H:i') }}</td>
                            <td class="py-3.5 px-6 font-semibold text-slate-800">{{ $t->description }}</td>
                            <td class="py-3.5 px-6">
                                <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $t->type === 'credit' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $t->type === 'credit' ? 'Setoran (Kredit)' : 'Penarikan (Debit)' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-6 font-bold {{ $t->type === 'credit' ? 'text-emerald-600' : 'text-amber-700' }}">
                                {{ $t->type === 'credit' ? '+' : '-' }}Rp {{ number_format($t->amount, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-6 text-right font-mono font-bold text-slate-800">
                                Rp {{ number_format($t->balance_after, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">Belum ada mutasi rekening.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
