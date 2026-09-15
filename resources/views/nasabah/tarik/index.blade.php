@extends('layouts.app')

@section('title', 'Fitur 3: Melihat Riwayat Penarikan - Nasabah')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 mb-2">
                <i class="fa-solid fa-money-bill-transfer"></i> Fitur 3 Nasabah: Melihat Riwayat Penarikan
            </span>
            <h1 class="text-2xl font-extrabold text-slate-900">Riwayat Penarikan Saldo Nasabah</h1>
            <p class="text-xs text-slate-500 mt-1">
                Sistem menampilkan riwayat penarikan saldo nasabah yang meliputi tanggal penarikan, jumlah saldo yang ditarik, dan status penarikan.
            </p>
        </div>
        <a href="{{ route('nasabah.tarik.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-bold text-xs text-white bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 shadow-md shadow-amber-500/20 transition">
            <i class="fa-solid fa-hand-holding-dollar"></i> Ajukan Penarikan Saldo Baru
        </a>
    </div>

    <!-- Info Saldo Terkini -->
    <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Saldo Terkini yang Dapat Ditarik</span>
            <p class="text-3xl font-black text-emerald-700 mt-1">
                Rp {{ number_format($profile->balance, 0, ',', '.') }}
            </p>
        </div>
        <a href="{{ route('nasabah.tarik.create') }}" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition self-start sm:self-auto shadow-sm">
            Tarik Saldo Sekarang
        </a>
    </div>

    <!-- Tabel Riwayat Penarikan Sesuai Konstruksi -->
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        @if($withdrawals->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[11px] border-b border-slate-100">
                        <tr>
                            <th class="py-4 px-6">Tanggal Penarikan</th>
                            <th class="py-4 px-6">Kode Pengajuan</th>
                            <th class="py-4 px-6">Tujuan / Rekening</th>
                            <th class="py-4 px-6">Jumlah Saldo yang Ditarik</th>
                            <th class="py-4 px-6">Status Penarikan</th>
                            <th class="py-4 px-6">Catatan Pengurus</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($withdrawals as $w)
                            <tr class="hover:bg-slate-50/80 transition">
                                <!-- Tanggal Penarikan -->
                                <td class="py-4 px-6 font-medium text-slate-800">
                                    {{ $w->created_at->format('d M Y, H:i') }} WIB
                                </td>

                                <!-- Kode Pengajuan -->
                                <td class="py-4 px-6 font-mono font-bold text-slate-700">
                                    {{ $w->code }}
                                </td>

                                <!-- Tujuan -->
                                <td class="py-4 px-6">
                                    <strong class="text-slate-800 block">{{ $w->channel }}</strong>
                                    <span class="text-[11px] text-slate-400">{{ $w->account_number }} a.n {{ $w->account_holder_name }}</span>
                                </td>

                                <!-- Jumlah Saldo yang Ditarik -->
                                <td class="py-4 px-6 font-extrabold text-slate-900 text-sm">
                                    Rp {{ number_format($w->amount, 0, ',', '.') }}
                                </td>

                                <!-- Status Penarikan -->
                                <td class="py-4 px-6">
                                    @if($w->status === 'approved')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase bg-emerald-100 text-emerald-800">
                                            <i class="fa-solid fa-circle-check text-[9px]"></i> Selesai Diproses
                                        </span>
                                    @elseif($w->status === 'rejected')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase bg-rose-100 text-rose-800">
                                            <i class="fa-solid fa-circle-xmark text-[9px]"></i> Ditolak
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase bg-amber-100 text-amber-800 animate-pulse">
                                            <i class="fa-solid fa-clock text-[9px]"></i> Menunggu Verifikasi
                                        </span>
                                    @endif
                                </td>

                                <!-- Catatan Pengurus -->
                                <td class="py-4 px-6 text-slate-500 text-[11px] max-w-xs">
                                    {{ $w->admin_notes ?? '-' }}
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
                <i class="fa-solid fa-money-bill-transfer text-4xl text-slate-300 mb-3 block"></i>
                <h3 class="text-base font-bold text-slate-800">Belum Ada Riwayat Penarikan</h3>
                <p class="text-xs text-slate-400 mt-1">Anda belum pernah mengajukan penarikan saldo tabungan.</p>
                <a href="{{ route('nasabah.tarik.create') }}" class="inline-block mt-4 px-4 py-2 rounded-xl text-xs font-bold text-amber-700 bg-amber-50 hover:bg-amber-100 transition">
                    Tarik Saldo Sekarang
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
