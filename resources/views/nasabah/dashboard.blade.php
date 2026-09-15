@extends('layouts.app')

@section('title', 'Dashboard Nasabah - Melihat Saldo')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    
    <!-- Welcome Header & Saldo Highlight -->
    <div class="p-6 sm:p-8 rounded-3xl bg-gradient-to-r from-emerald-800 via-teal-800 to-slate-900 text-white shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-2">
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 font-mono">
                No. Rekening: {{ $profile->no_rekening }}
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white">
                Selamat Datang, {{ $user->name }}!
            </h1>
            <p class="text-xs sm:text-sm text-emerald-100/80 max-w-xl">
                Aktor: <strong>Nasabah</strong> &bull; Kelola tabungan sampah Anda, pantau mutasi saldo, dan cek harga sampah terkini.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('nasabah.kartu') }}" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold text-xs transition flex items-center gap-2">
                <i class="fa-solid fa-qrcode"></i> Kartu Nasabah
            </a>
            <a href="{{ route('nasabah.tarik.create') }}" class="px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs shadow-lg shadow-amber-500/30 transition flex items-center gap-2">
                <i class="fa-solid fa-money-bill-transfer"></i> Tarik Saldo
            </a>
        </div>
    </div>

    <!-- FITUR 1 NASABAH: MELIHAT SALDO -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-600">Fitur 1 Nasabah</span>
                <h2 class="text-lg font-bold text-slate-900">Melihat Saldo Terkini</h2>
                <p class="text-xs text-slate-500">Jumlah saldo terkini yang dimiliki nasabah berdasarkan transaksi setoran dan penarikan yang telah diproses.</p>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-wallet"></i>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <!-- Saldo Utama -->
            <div class="p-6 rounded-2xl bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-200/80">
                <span class="text-xs font-bold text-emerald-800 uppercase tracking-wider block">Saldo Terkini Tersedia</span>
                <p class="text-3xl sm:text-4xl font-black text-emerald-700 mt-2">
                    Rp {{ number_format($profile->balance, 0, ',', '.') }}
                </p>
                <div class="mt-4 pt-3 border-t border-emerald-200/60 flex items-center justify-between text-xs">
                    <span class="text-slate-500">Status Rekening:</span>
                    <span class="font-bold text-emerald-700">Aktif & Siap Ditarik</span>
                </div>
            </div>

            <!-- Akumulasi Setoran Masuk -->
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80">
                <span class="text-xs font-bold text-slate-600 uppercase tracking-wider block">Total Setoran yang Diproses</span>
                <p class="text-2xl font-extrabold text-slate-800 mt-2">
                    Rp {{ number_format($totalSetorRp, 0, ',', '.') }}
                </p>
                <div class="mt-4 pt-3 border-t border-slate-200 flex items-center justify-between text-xs text-slate-500">
                    <span>Total Berat Disetor:</span>
                    <strong class="text-slate-700">{{ number_format($totalSetorKg, 1, ',', '.') }} kg</strong>
                </div>
            </div>

            <!-- Akumulasi Penarikan Keluar -->
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80">
                <span class="text-xs font-bold text-slate-600 uppercase tracking-wider block">Total Penarikan Selesai</span>
                <p class="text-2xl font-extrabold text-amber-700 mt-2">
                    Rp {{ number_format($totalTarikRp, 0, ',', '.') }}
                </p>
                <div class="mt-4 pt-3 border-t border-slate-200 flex items-center justify-between text-xs text-slate-500">
                    <span>Mutasi Penarikan:</span>
                    <a href="{{ route('nasabah.tarik.index') }}" class="font-bold text-amber-600 hover:underline">Riwayat Penarikan &rarr;</a>
                </div>
            </div>
        </div>
    </div>

    <!-- 4 Shortcut Grid Menu Nasabah -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ route('nasabah.dashboard') }}" class="p-5 rounded-3xl bg-emerald-600 text-white shadow-lg shadow-emerald-600/20 flex flex-col justify-between h-36 transition group">
            <div class="flex items-center justify-between">
                <span class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center text-sm"><i class="fa-solid fa-wallet"></i></span>
                <span class="text-[10px] font-bold uppercase tracking-wider bg-white/20 px-2 py-0.5 rounded-full">Fitur 1</span>
            </div>
            <div>
                <h3 class="font-bold text-sm">Melihat Saldo</h3>
                <p class="text-[11px] text-emerald-100 mt-0.5">Saldo aktif: Rp {{ number_format($profile->balance, 0, ',', '.') }}</p>
            </div>
        </a>

        <a href="{{ route('nasabah.setor.index') }}" class="p-5 rounded-3xl bg-white border border-slate-200 hover:border-emerald-400 hover:shadow-lg hover:shadow-emerald-500/10 flex flex-col justify-between h-36 transition group">
            <div class="flex items-center justify-between">
                <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm"><i class="fa-solid fa-clock-rotate-left"></i></span>
                <span class="text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full">Fitur 2</span>
            </div>
            <div>
                <h3 class="font-bold text-sm text-slate-800 group-hover:text-emerald-700">Melihat Riwayat Setoran</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Tanggal, jenis sampah, berat & rupiah</p>
            </div>
        </a>

        <a href="{{ route('nasabah.tarik.index') }}" class="p-5 rounded-3xl bg-white border border-slate-200 hover:border-emerald-400 hover:shadow-lg hover:shadow-emerald-500/10 flex flex-col justify-between h-36 transition group">
            <div class="flex items-center justify-between">
                <span class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm"><i class="fa-solid fa-money-bill-transfer"></i></span>
                <span class="text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full">Fitur 3</span>
            </div>
            <div>
                <h3 class="font-bold text-sm text-slate-800 group-hover:text-amber-700">Melihat Riwayat Penarikan</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Tanggal, nominal ditarik & status</p>
            </div>
        </a>

        <a href="{{ route('nasabah.harga') }}" class="p-5 rounded-3xl bg-white border border-slate-200 hover:border-emerald-400 hover:shadow-lg hover:shadow-emerald-500/10 flex flex-col justify-between h-36 transition group">
            <div class="flex items-center justify-between">
                <span class="w-8 h-8 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-sm"><i class="fa-solid fa-tags"></i></span>
                <span class="text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full">Fitur 4</span>
            </div>
            <div>
                <h3 class="font-bold text-sm text-slate-800 group-hover:text-teal-700">Melihat Harga Sampah</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Katalog harga per kg berlaku</p>
            </div>
        </a>
    </div>

    <!-- Riwayat Setoran & Penarikan Cuplikan -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Riwayat Setoran -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-bold">2</span>
                    <h3 class="font-bold text-sm text-slate-800">Riwayat Setoran Terbaru</h3>
                </div>
                <a href="{{ route('nasabah.setor.index') }}" class="text-xs font-bold text-emerald-600 hover:underline">
                    Semua Setoran &rarr;
                </a>
            </div>

            @if($recentDeposits->count() > 0)
                <div class="divide-y divide-slate-100 text-xs">
                    @foreach($recentDeposits as $d)
                        <div class="py-3 flex items-center justify-between">
                            <div>
                                <strong class="text-slate-800 block font-mono">{{ $d->code }}</strong>
                                <span class="text-[11px] text-slate-400">{{ $d->created_at->format('d/m/Y H:i') }} &bull; {{ $d->total_weight_kg }} kg</span>
                            </div>
                            <div class="text-right">
                                <span class="font-extrabold text-emerald-600 block">+Rp {{ number_format($d->total_amount, 0, ',', '.') }}</span>
                                <a href="{{ route('nasabah.setor.show', $d->id) }}" class="text-[10px] text-teal-600 hover:underline">Lihat Slip</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="py-6 text-center text-xs text-slate-400">Belum ada riwayat setoran.</p>
            @endif
        </div>

        <!-- Riwayat Penarikan -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center text-xs font-bold">3</span>
                    <h3 class="font-bold text-sm text-slate-800">Riwayat Penarikan Terbaru</h3>
                </div>
                <a href="{{ route('nasabah.tarik.index') }}" class="text-xs font-bold text-amber-600 hover:underline">
                    Semua Penarikan &rarr;
                </a>
            </div>

            @if($recentWithdrawals->count() > 0)
                <div class="divide-y divide-slate-100 text-xs">
                    @foreach($recentWithdrawals as $w)
                        <div class="py-3 flex items-center justify-between">
                            <div>
                                <strong class="text-slate-800 block font-mono">{{ $w->code }}</strong>
                                <span class="text-[11px] text-slate-400">{{ $w->created_at->format('d/m/Y') }} &bull; {{ $w->channel }}</span>
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-slate-900 block">-Rp {{ number_format($w->amount, 0, ',', '.') }}</span>
                                <span class="text-[10px] font-bold uppercase {{ $w->status === 'approved' ? 'text-emerald-600' : ($w->status === 'rejected' ? 'text-rose-600' : 'text-amber-600') }}">
                                    {{ $w->status === 'approved' ? 'Selesai' : ($w->status === 'rejected' ? 'Ditolak' : 'Pending') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="py-6 text-center text-xs text-slate-400">Belum ada riwayat penarikan saldo.</p>
            @endif
        </div>

    </div>

</div>
@endsection
