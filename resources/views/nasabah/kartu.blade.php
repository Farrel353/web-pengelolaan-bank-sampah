@extends('layouts.app')

@section('title', 'Kartu Anggota Digital QR - EcoBank')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">
    <div class="text-center max-w-xl mx-auto">
        <span class="text-xs font-bold uppercase tracking-widest text-emerald-600">Identitas Virtual Resmi</span>
        <h1 class="text-3xl font-extrabold text-slate-900 mt-1">Kartu Anggota Digital</h1>
        <p class="text-slate-500 text-xs sm:text-sm mt-2">
            Tunjukkan QR Code ini kepada petugas penimbang saat menyetorkan sampah di lokasi Bank Sampah EcoBank agar saldo langsung terisi.
        </p>
    </div>

    <!-- Virtual Member Card (ATM Style Card) -->
    <div class="max-w-md mx-auto">
        <div id="member-card" class="relative overflow-hidden rounded-3xl p-7 bg-gradient-to-tr from-emerald-950 via-emerald-800 to-teal-700 text-white shadow-2xl border border-emerald-500/30 flex flex-col justify-between h-72 sm:h-80">
            <!-- Decorative Elements -->
            <div class="absolute -right-12 -top-12 w-48 h-48 rounded-full bg-emerald-400/10 blur-2xl"></div>
            <div class="absolute -left-12 -bottom-12 w-48 h-48 rounded-full bg-teal-400/10 blur-2xl"></div>

            <!-- Card Header -->
            <div class="flex items-center justify-between relative z-10">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-white/15 backdrop-blur-md flex items-center justify-center text-white text-base">
                        <i class="fa-solid fa-recycle"></i>
                    </div>
                    <div>
                        <span class="text-base font-extrabold tracking-tight">EcoBank</span>
                        <span class="block text-[9px] uppercase tracking-widest text-emerald-300 -mt-1">Member Card</span>
                    </div>
                </div>
                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-emerald-400/20 text-emerald-300 border border-emerald-300/30">
                    {{ $profile->tier }}
                </span>
            </div>

            <!-- Card Middle / Chip & QR -->
            <div class="flex items-center justify-between relative z-10 my-auto">
                <div class="space-y-2">
                    <!-- EMV Chip Simulation -->
                    <div class="w-11 h-8 rounded-lg bg-gradient-to-r from-amber-200 to-amber-400 border border-amber-500/40 flex items-center justify-center shadow-inner">
                        <div class="grid grid-cols-2 gap-1 w-7 h-5 opacity-40">
                            <div class="border-b border-amber-800"></div>
                            <div class="border-b border-amber-800"></div>
                        </div>
                    </div>
                    <p class="font-mono text-sm tracking-widest text-emerald-200">
                        {{ chunk_split($profile->no_rekening, 4, ' ') }}
                    </p>
                </div>

                <!-- QR Code Box -->
                <div class="p-2 rounded-2xl bg-white text-slate-900 shadow-md flex items-center justify-center">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode($profile->qr_code_token ?? $profile->no_rekening) }}" alt="QR Code Nasabah" class="w-18 h-18 rounded-lg">
                </div>
            </div>

            <!-- Card Footer -->
            <div class="flex items-end justify-between relative z-10 pt-2 border-t border-white/10">
                <div>
                    <span class="text-[9px] uppercase tracking-wider text-emerald-300 block">Nama Nasabah</span>
                    <span class="text-sm font-bold tracking-wide uppercase">{{ $user->name }}</span>
                </div>
                <div class="text-right">
                    <span class="text-[9px] uppercase tracking-wider text-emerald-300 block">Status Akun</span>
                    <span class="text-xs font-semibold text-emerald-200 uppercase flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Aktif
                    </span>
                </div>
            </div>
        </div>

        <!-- Action Button -->
        <div class="mt-6 flex justify-center gap-3">
            <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs flex items-center gap-2 shadow-sm transition">
                <i class="fa-solid fa-print"></i> Cetak Kartu Digital
            </button>
            <a href="{{ route('nasabah.dashboard') }}" class="px-5 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-semibold text-xs transition">
                Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
