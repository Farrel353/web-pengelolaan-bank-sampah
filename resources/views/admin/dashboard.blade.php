@extends('layouts.app')

@section('title', 'Dashboard Pengurus/Admin - Sistem Bank Sampah')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    
    <!-- Welcome Header Pengurus -->
    <div class="p-6 sm:p-8 rounded-3xl bg-slate-900 text-white shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-2">
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-400/30">
                <i class="fa-solid fa-gear mr-1"></i> Aktor: Pengurus / Admin
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white">
                Dashboard Manajemen Bank Sampah
            </h1>
            <p class="text-xs sm:text-sm text-slate-300 max-w-xl">
                Akses cepat ke seluruh 5 fitur pengurus: kelola data nasabah, jenis & harga sampah, pencatatan setoran, proses penarikan, dan laporan transaksi.
            </p>
        </div>

        <div class="flex flex-wrap gap-2.5">
            <a href="{{ route('admin.timbang.index') }}" class="px-5 py-3 rounded-2xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-extrabold text-xs uppercase tracking-wider shadow-lg shadow-amber-500/30 transition flex items-center gap-2">
                <i class="fa-solid fa-scale-balanced text-sm"></i> Catat Setoran Sampah
            </a>
            <a href="{{ route('admin.laporan.index') }}" class="px-4 py-3 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white font-semibold text-xs transition flex items-center gap-2">
                <i class="fa-solid fa-chart-column"></i> Laporan Transaksi
            </a>
        </div>
    </div>

    <!-- 5 FITUR UTAMA PENGURUS/ADMIN CARDS (SESUAI DIAGRAM) -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-extrabold text-slate-900 uppercase tracking-wider">5 Fitur Pengurus / Admin</h2>
            <span class="text-xs text-slate-400">Pilih modul operasional yang ingin dikelola</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- 1. Mengelola Data Nasabah -->
            <a href="{{ route('admin.nasabah.index') }}" class="p-5 rounded-3xl bg-white border border-slate-200 hover:border-amber-400 hover:shadow-xl hover:shadow-amber-500/10 transition group flex flex-col justify-between h-44">
                <div class="flex items-center justify-between">
                    <span class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-base"><i class="fa-solid fa-users"></i></span>
                    <span class="text-[10px] font-extrabold uppercase bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">Fitur 1</span>
                </div>
                <div>
                    <h3 class="font-bold text-xs text-slate-900 group-hover:text-blue-600 transition leading-snug">Mengelola Data Nasabah</h3>
                    <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">Tambah, ubah, hapus, & lihat riwayat transaksi nasabah.</p>
                </div>
            </a>

            <!-- 2. Mengelola Jenis & Harga Sampah -->
            <a href="{{ route('admin.sampah.index') }}" class="p-5 rounded-3xl bg-white border border-slate-200 hover:border-amber-400 hover:shadow-xl hover:shadow-amber-500/10 transition group flex flex-col justify-between h-44">
                <div class="flex items-center justify-between">
                    <span class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-base"><i class="fa-solid fa-tags"></i></span>
                    <span class="text-[10px] font-extrabold uppercase bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full">Fitur 2</span>
                </div>
                <div>
                    <h3 class="font-bold text-xs text-slate-900 group-hover:text-emerald-600 transition leading-snug">Mengelola Jenis & Harga</h3>
                    <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">Tambah jenis sampah baru & tentukan harga per kg berlaku.</p>
                </div>
            </a>

            <!-- 3. Mencatat Setoran Sampah -->
            <a href="{{ route('admin.timbang.index') }}" class="p-5 rounded-3xl bg-amber-500 text-white shadow-lg shadow-amber-500/25 hover:bg-amber-600 transition group flex flex-col justify-between h-44">
                <div class="flex items-center justify-between">
                    <span class="w-10 h-10 rounded-2xl bg-white/20 flex items-center justify-center text-base"><i class="fa-solid fa-scale-balanced"></i></span>
                    <span class="text-[10px] font-extrabold uppercase bg-white/20 px-2 py-0.5 rounded-full">Fitur 3</span>
                </div>
                <div>
                    <h3 class="font-bold text-xs leading-snug">Mencatat Setoran Sampah</h3>
                    <p class="text-[11px] text-amber-100 mt-1 leading-relaxed">Catat berat (kg), hitung nilai rupiah, & otomatis tambah saldo.</p>
                </div>
            </a>

            <!-- 4. Memproses Penarikan Saldo -->
            <a href="{{ route('admin.penarikan.index') }}" class="p-5 rounded-3xl bg-white border border-slate-200 hover:border-amber-400 hover:shadow-xl hover:shadow-amber-500/10 transition group flex flex-col justify-between h-44">
                <div class="flex items-center justify-between">
                    <span class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-base"><i class="fa-solid fa-hand-holding-dollar"></i></span>
                    <span class="text-[10px] font-extrabold uppercase bg-amber-100 text-amber-800 px-2 py-0.5 rounded-full">Fitur 4</span>
                </div>
                <div>
                    <h3 class="font-bold text-xs text-slate-900 group-hover:text-amber-600 transition leading-snug">Memproses Penarikan</h3>
                    <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">Lihat permintaan, proses, & kurangi saldo nasabah.</p>
                </div>
            </a>

            <!-- 5. Melihat Laporan Transaksi -->
            <a href="{{ route('admin.laporan.index') }}" class="p-5 rounded-3xl bg-white border border-slate-200 hover:border-amber-400 hover:shadow-xl hover:shadow-amber-500/10 transition group flex flex-col justify-between h-44">
                <div class="flex items-center justify-between">
                    <span class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-base"><i class="fa-solid fa-chart-column"></i></span>
                    <span class="text-[10px] font-extrabold uppercase bg-purple-100 text-purple-800 px-2 py-0.5 rounded-full">Fitur 5</span>
                </div>
                <div>
                    <h3 class="font-bold text-xs text-slate-900 group-hover:text-purple-600 transition leading-snug">Melihat Laporan</h3>
                    <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">Pantau data transaksi setoran & penarikan serta ekspor.</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Ringkasan Operasional & Antrean Penting -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Antrean Permintaan Penarikan -->
        <div class="lg:col-span-7 bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="font-bold text-sm text-slate-900">Permintaan Penarikan Saldo yang Menunggu Diproses</h3>
                    <p class="text-xs text-slate-400">Verifikasi dan kurangi saldo nasabah secara transparan</p>
                </div>
                <a href="{{ route('admin.penarikan.index') }}" class="text-xs font-bold text-amber-600 hover:underline">
                    Kelola Semua &rarr;
                </a>
            </div>

            @if($pendingWithdrawals->count() > 0)
                <div class="divide-y divide-slate-100 text-xs">
                    @foreach($pendingWithdrawals as $pw)
                        <div class="py-3.5 flex items-center justify-between gap-3">
                            <div>
                                <strong class="text-slate-800 block">{{ $pw->user->name }}</strong>
                                <span class="text-[11px] text-slate-400">{{ $pw->channel }} &bull; {{ $pw->account_number }} a.n {{ $pw->account_holder_name }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="font-bold text-slate-900">Rp {{ number_format($pw->amount, 0, ',', '.') }}</span>
                                <form action="{{ route('admin.penarikan.approve', $pw->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition">
                                        Proses & Kurangi Saldo
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="py-8 text-center text-xs text-slate-400">Tidak ada permohonan penarikan saldo pending.</p>
            @endif
        </div>

        <!-- 5 Setoran Terakhir yang Telah Dicatat -->
        <div class="lg:col-span-5 bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="font-bold text-sm text-slate-900">Setoran Sampah Terakhir</h3>
                    <p class="text-xs text-slate-400">Pencatatan timbangan terbaru</p>
                </div>
                <a href="{{ route('admin.timbang.index') }}" class="text-xs font-bold text-emerald-600 hover:underline">
                    + Catat Baru &rarr;
                </a>
            </div>

            @if($recentDeposits->count() > 0)
                <div class="divide-y divide-slate-100 text-xs">
                    @foreach($recentDeposits as $rd)
                        <div class="py-3 flex items-center justify-between">
                            <div>
                                <strong class="text-slate-800 block">{{ $rd->user->name }}</strong>
                                <span class="text-[11px] text-slate-400">{{ $rd->created_at->format('d/m/Y') }} &bull; {{ $rd->total_weight_kg }} kg</span>
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-emerald-600 block">+Rp {{ number_format($rd->total_amount, 0, ',', '.') }}</span>
                                <a href="{{ route('admin.timbang.receipt', $rd->id) }}" class="text-[10px] text-slate-400 hover:underline">Lihat Struk</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="py-8 text-center text-xs text-slate-400">Belum ada catatan setoran.</p>
            @endif
        </div>
    </div>

</div>
@endsection
