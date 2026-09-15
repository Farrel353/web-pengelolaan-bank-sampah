@extends('layouts.app')

@section('title', 'Fitur 4: Memproses Penarikan Saldo - Pengurus')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 mb-2">
                <i class="fa-solid fa-hand-holding-dollar"></i> Fitur 4 Pengurus: Memproses Penarikan Saldo
            </span>
            <h1 class="text-2xl font-extrabold text-slate-900">Pemrosesan Penarikan Saldo Nasabah</h1>
            <p class="text-xs text-slate-500 mt-1">
                Pengurus dapat melihat permintaan penarikan, memproses penarikan, dan mengurangi saldo nasabah sesuai jumlah yang ditarik.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3.5 py-1.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-bold flex items-center gap-1.5">
                <i class="fa-solid fa-clock text-amber-600"></i> {{ $pendingCount }} Permintaan Perlu Diproses
            </span>
        </div>
    </div>

    <!-- Filter Status Permintaan -->
    <div class="flex items-center gap-2 overflow-x-auto text-xs font-bold">
        <a href="{{ route('admin.penarikan.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-xl transition {{ $status === 'pending' ? 'bg-amber-500 text-white shadow-md shadow-amber-500/20' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' }}">
            Permintaan Menunggu Diproses ({{ $pendingCount }})
        </a>
        <a href="{{ route('admin.penarikan.index', ['status' => 'approved']) }}" class="px-4 py-2 rounded-xl transition {{ $status === 'approved' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' }}">
            Telah Diproses (Selesai)
        </a>
        <a href="{{ route('admin.penarikan.index', ['status' => 'rejected']) }}" class="px-4 py-2 rounded-xl transition {{ $status === 'rejected' ? 'bg-rose-600 text-white shadow-md shadow-rose-600/20' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' }}">
            Ditolak
        </a>
        <a href="{{ route('admin.penarikan.index', ['status' => 'all']) }}" class="px-4 py-2 rounded-xl transition {{ $status === 'all' ? 'bg-slate-800 text-white' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' }}">
            Semua Riwayat
        </a>
    </div>

    <!-- Tabel Daftar Permintaan Penarikan -->
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        @if($withdrawals->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[11px] border-b border-slate-100">
                        <tr>
                            <th class="py-4 px-6">Tanggal Permintaan</th>
                            <th class="py-4 px-6">Kode Pengajuan</th>
                            <th class="py-4 px-6">Nama & Rekening Nasabah</th>
                            <th class="py-4 px-6">Tujuan Transfer</th>
                            <th class="py-4 px-6">Jumlah Ditarik</th>
                            <th class="py-4 px-6">Saldo Nasabah Saat Ini</th>
                            <th class="py-4 px-6">Status</th>
                            <th class="py-4 px-6 text-right">Aksi Pengurus</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($withdrawals as $w)
                            <tr class="hover:bg-slate-50/80 transition">
                                <!-- Tanggal -->
                                <td class="py-4 px-6 text-slate-700 font-medium">
                                    {{ $w->created_at->format('d M Y, H:i') }}
                                </td>

                                <!-- Kode -->
                                <td class="py-4 px-6 font-mono font-bold text-slate-800">
                                    {{ $w->code }}
                                </td>

                                <!-- Nasabah -->
                                <td class="py-4 px-6">
                                    <strong class="text-slate-900 block">{{ $w->user->name }}</strong>
                                    <span class="text-[10px] text-slate-400 font-mono">{{ $w->user->nasabahProfile?->no_rekening ?? '-' }}</span>
                                </td>

                                <!-- Tujuan Transfer -->
                                <td class="py-4 px-6">
                                    <strong class="text-slate-800 block">{{ $w->channel }}</strong>
                                    <span class="text-[11px] text-slate-500">{{ $w->account_number }} a.n {{ $w->account_holder_name }}</span>
                                </td>

                                <!-- Jumlah Ditarik -->
                                <td class="py-4 px-6 font-extrabold text-slate-900 text-sm">
                                    Rp {{ number_format($w->amount, 0, ',', '.') }}
                                </td>

                                <!-- Saldo Nasabah Saat Ini -->
                                <td class="py-4 px-6 font-bold text-emerald-700">
                                    Rp {{ number_format($w->user->nasabahProfile?->balance ?? 0, 0, ',', '.') }}
                                </td>

                                <!-- Status -->
                                <td class="py-4 px-6">
                                    @if($w->status === 'approved')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-emerald-100 text-emerald-800">
                                            <i class="fa-solid fa-check"></i> Selesai Diproses
                                        </span>
                                    @elseif($w->status === 'rejected')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-rose-100 text-rose-800">
                                            <i class="fa-solid fa-xmark"></i> Ditolak
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-amber-100 text-amber-800 animate-pulse">
                                            <i class="fa-solid fa-clock"></i> Menunggu Diproses
                                        </span>
                                    @endif
                                </td>

                                <!-- Aksi Pengurus -->
                                <td class="py-4 px-6 text-right">
                                    @if($w->status === 'pending')
                                        <div class="flex items-center justify-end gap-1.5">
                                            <!-- Form Memproses Penarikan & Mengurangi Saldo -->
                                            <form action="{{ route('admin.penarikan.approve', $w->id) }}" method="POST" onsubmit="return confirm('Konfirmasi: Proses penarikan dan kurangi saldo nasabah {{ addslashes($w->user->name) }} sebesar Rp {{ number_format($w->amount, 0, ',', '.') }}?')">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition flex items-center gap-1">
                                                    <i class="fa-solid fa-check"></i> Proses & Kurangi Saldo
                                                </button>
                                            </form>

                                            <!-- Form Tolak -->
                                            <form action="{{ route('admin.penarikan.reject', $w->id) }}" method="POST" onsubmit="return confirm('Tolak permintaan penarikan ini?')">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs transition">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-[11px] text-slate-400">
                                            Diproses oleh: {{ $w->approver->name ?? 'Pengurus' }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $withdrawals->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <i class="fa-solid fa-inbox text-4xl text-slate-300 mb-3 block"></i>
                <h3 class="text-base font-bold text-slate-800">Tidak Ada Permintaan Penarikan</h3>
                <p class="text-xs text-slate-400 mt-1">Tidak ada data penarikan pada kategori filter ini.</p>
            </div>
        @endif
    </div>
</div>
@endsection
