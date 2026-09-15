@extends('layouts.app')

@section('title', 'Beranda - Solusi Cerdas Pengelolaan Bank Sampah Digital')

@section('content')
<!-- Hero Section -->
<section class="relative overflow-hidden bg-gradient-to-b from-emerald-900 via-teal-900 to-slate-900 text-white py-20 lg:py-28">
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#34d399_1px,transparent_1px)] [background-size:24px_24px]"></div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-7 space-y-6">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-xs font-semibold backdrop-blur-md">
                    <span class="flex h-2 w-2 rounded-full bg-emerald-400 animate-ping"></span>
                    Bank Sampah Digital Modern & Terintegrasi
                </div>
                
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight">
                    Ubah Sampah Jadi <span class="bg-gradient-to-r from-emerald-400 via-teal-300 to-amber-300 bg-clip-text text-transparent">Cuan & Poin Berkah</span> untuk Bumi
                </h1>
                
                <p class="text-slate-300 text-base sm:text-lg leading-relaxed max-w-2xl font-light">
                    Kumpulkan dan pilah sampah rumah tangga Anda. Setorkan ke EcoBank, dapatkan saldo rupiah yang bisa dicairkan ke rekening & e-wallet, kumpulkan eco-points, serta pantau reduksi jejak karbon secara real-time.
                </p>

                <div class="flex flex-wrap gap-4 pt-2">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-2xl font-bold text-white bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 shadow-xl shadow-emerald-500/30 active:scale-95 transition-all text-sm">
                        <i class="fa-solid fa-user-plus"></i> Buka Buku Tabungan Gratis
                    </a>
                    <a href="{{ route('kalkulator') }}" class="inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-2xl font-bold text-white bg-white/10 hover:bg-white/20 border border-white/20 backdrop-blur-md active:scale-95 transition-all text-sm">
                        <i class="fa-solid fa-calculator text-emerald-400"></i> Hitung Simulasi Sampah
                    </a>
                </div>

                <!-- Keunggulan Cepat -->
                <div class="grid grid-cols-3 gap-4 pt-4 border-t border-white/10 text-xs text-slate-300">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-bolt text-emerald-400 text-sm"></i>
                        <span>Timbang Cepat & Saldo Instan</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-truck-fast text-teal-400 text-sm"></i>
                        <span>Bisa Dijemput ke Rumah</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-award text-amber-400 text-sm"></i>
                        <span>Poin Reward & Sembako</span>
                    </div>
                </div>
            </div>

            <!-- Hero Live Impact Counter Card -->
            <div class="lg:col-span-5">
                <div class="p-8 rounded-3xl bg-white/10 backdrop-blur-xl border border-white/20 shadow-2xl space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-white/10">
                        <div>
                            <h3 class="text-lg font-bold text-white">Dampak Nyata Bersama</h3>
                            <p class="text-xs text-slate-300">Statistik agregat Bank Sampah terverifikasi</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-earth-americas"></i>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 rounded-2xl bg-emerald-950/40 border border-emerald-500/20">
                            <p class="text-xs text-emerald-300 font-medium">Sampah Terkelola</p>
                            <p class="text-2xl font-extrabold text-white mt-1">{{ number_format($totalSampahKg, 1, ',', '.') }} <span class="text-xs text-emerald-400 font-normal">kg</span></p>
                        </div>

                        <div class="p-4 rounded-2xl bg-teal-950/40 border border-teal-500/20">
                            <p class="text-xs text-teal-300 font-medium">Dana Disalurkan</p>
                            <p class="text-2xl font-extrabold text-white mt-1">Rp {{ number_format($totalDanaDisalurkan, 0, ',', '.') }}</p>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-900/60 border border-white/10">
                            <p class="text-xs text-slate-300 font-medium">Nasabah Aktif</p>
                            <p class="text-2xl font-extrabold text-white mt-1">{{ number_format($totalNasabah, 0, ',', '.') }} <span class="text-xs text-slate-400 font-normal">warga</span></p>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-900/60 border border-white/10">
                            <p class="text-xs text-slate-300 font-medium">Reduksi Emisi</p>
                            <p class="text-2xl font-extrabold text-emerald-400 mt-1">{{ number_format($totalCo2Tereduksi, 1, ',', '.') }} <span class="text-xs text-emerald-400/80 font-normal">kg CO₂e</span></p>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-gradient-to-r from-emerald-600/30 to-teal-600/30 border border-emerald-400/30 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2.5">
                            <i class="fa-solid fa-qrcode text-xl text-emerald-300"></i>
                            <div>
                                <p class="font-bold text-white">Kartu Nasabah Digital QR</p>
                                <p class="text-[11px] text-emerald-200">Tinggal scan saat setor sampah di lokasi</p>
                            </div>
                        </div>
                        <a href="{{ route('katalog') }}" class="px-3 py-1.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-semibold transition">
                            Lihat Harga
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section Katalog Harga Sampah Populer -->
<section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-10">
        <div>
            <div class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-emerald-600 mb-2">
                <i class="fa-solid fa-tags"></i> Standar Harga Beli Terkini
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Katalog Harga Sampah</h2>
            <p class="text-slate-500 text-sm mt-1">Harga kompetitif yang terus disesuaikan secara berkala dengan pasar daur ulang.</p>
        </div>
        <a href="{{ route('katalog') }}" class="mt-4 md:mt-0 inline-flex items-center gap-2 text-sm font-bold text-emerald-700 hover:text-emerald-800">
            Lihat Semua Kategori & Harga <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($featuredItems as $item)
            <div class="p-6 rounded-3xl bg-white border border-slate-200/80 hover:border-emerald-300 hover:shadow-xl hover:shadow-emerald-500/10 transition group flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                            {{ $item->category->name }}
                        </span>
                        <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2.5 py-0.5 rounded-lg">
                            +{{ $item->points_per_unit }} Poin / {{ $item->unit }}
                        </span>
                    </div>

                    <h3 class="text-lg font-bold text-slate-800 group-hover:text-emerald-700 transition">
                        {{ $item->name }}
                    </h3>
                    <p class="text-xs text-slate-500 mt-2 line-clamp-2 leading-relaxed">
                        {{ $item->description }}
                    </p>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                    <div>
                        <span class="text-[11px] text-slate-400 block">Harga Beli Nasabah</span>
                        <span class="text-xl font-extrabold text-emerald-600">
                            Rp {{ number_format($item->buy_price_per_unit, 0, ',', '.') }}
                        </span>
                        <span class="text-xs text-slate-400">/ {{ $item->unit }}</span>
                    </div>

                    <a href="{{ route('kalkulator') }}" class="w-10 h-10 rounded-2xl bg-slate-100 group-hover:bg-emerald-600 group-hover:text-white flex items-center justify-center text-slate-600 transition" title="Simulasikan nilai sampah ini">
                        <i class="fa-solid fa-calculator text-sm"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</section>

<!-- Section Alur Mudah Menabung Sampah -->
<section class="bg-emerald-900 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="text-xs font-bold tracking-widest text-emerald-400 uppercase">Mudah & Menyenangkan</span>
            <h2 class="text-3xl font-extrabold mt-2">4 Langkah Menjadi Nasabah Cerdas</h2>
            <p class="text-slate-300 text-sm mt-2">Ubah tumpukan kardus dan botol plastik di rumah menjadi pundi-pundi tabungan.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="relative p-6 rounded-3xl bg-white/5 border border-white/10 backdrop-blur-md">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center font-bold text-lg mb-4 shadow-lg shadow-emerald-500/30">
                    1
                </div>
                <h3 class="text-base font-bold text-white mb-2">Pilah dari Rumah</h3>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Pisahkan sampah anorganik (botol plastik, kardus, kaleng, kaca) dari sampah basah/organik.
                </p>
            </div>

            <div class="relative p-6 rounded-3xl bg-white/5 border border-white/10 backdrop-blur-md">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center font-bold text-lg mb-4 shadow-lg shadow-emerald-500/30">
                    2
                </div>
                <h3 class="text-base font-bold text-white mb-2">Setor atau Dijemput</h3>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Bawa langsung ke titik drop-point terdekat atau ajukan request penjemputan dari menu nasabah.
                </p>
            </div>

            <div class="relative p-6 rounded-3xl bg-white/5 border border-white/10 backdrop-blur-md">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center font-bold text-lg mb-4 shadow-lg shadow-emerald-500/30">
                    3
                </div>
                <h3 class="text-base font-bold text-white mb-2">Timbang Real-time</h3>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Petugas menimbang sampah secara transparan menggunakan timbangan digital resmi.
                </p>
            </div>

            <div class="relative p-6 rounded-3xl bg-white/5 border border-white/10 backdrop-blur-md">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center font-bold text-lg mb-4 shadow-lg shadow-emerald-500/30">
                    4
                </div>
                <h3 class="text-base font-bold text-white mb-2">Saldo & Poin Cair</h3>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Saldo otomatis masuk ke buku tabungan digital. Bisa ditarik ke GoPay, OVO, DANA, rekening bank, atau ditukar sembako.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Call To Action Banner -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="rounded-3xl bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-800 text-white p-8 sm:p-12 shadow-2xl relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-8">
        <div class="space-y-4 max-w-xl">
            <h2 class="text-2xl sm:text-3xl font-extrabold leading-tight">Siap Mulai Menabung & Melindungi Lingkungan Hari Ini?</h2>
            <p class="text-emerald-100 text-sm">
                Pendaftaran nasabah gratis 100% dan langsung mendapatkan bonus selamat datang 100 Eco-Points.
            </p>
        </div>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('register') }}" class="px-6 py-3.5 rounded-2xl bg-white text-emerald-800 font-bold text-sm hover:bg-emerald-50 shadow-lg active:scale-95 transition">
                Daftar Nasabah Sekarang
            </a>
            <a href="{{ route('edukasi') }}" class="px-6 py-3.5 rounded-2xl bg-emerald-700/50 hover:bg-emerald-700 text-white font-bold text-sm border border-emerald-400/30 active:scale-95 transition">
                Pelajari Panduan 3R
            </a>
        </div>
    </div>
</section>
@endsection
