@extends('layouts.app')

@section('title', 'Katalog & Harga Sampah - EcoBank')

@section('content')
<div class="bg-gradient-to-b from-emerald-50 to-slate-50 py-10 border-b border-slate-200/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <span class="text-xs font-bold uppercase tracking-widest text-emerald-600">Daftar Komoditas Daur Ulang</span>
            <h1 class="text-3xl font-extrabold text-slate-900 mt-1">Katalog Harga Sampah Terkini</h1>
            <p class="text-slate-600 text-sm mt-2">
                Pembaruan harga beli resmi dari Bank Sampah EcoBank. Pilah sampah Anda dalam kondisi bersih dan kering untuk mendapatkan nilai maksimal.
            </p>
        </div>

        <!-- Filter & Search Bar -->
        <div class="mt-8 flex flex-col md:flex-row gap-4 justify-between items-stretch md:items-center">
            <!-- Category Chips -->
            <div class="flex items-center gap-2 overflow-x-auto pb-2 md:pb-0 scrollbar-none text-xs">
                <a href="{{ route('katalog') }}" class="px-4 py-2 rounded-xl font-semibold whitespace-nowrap transition {{ empty($selectedCategory) ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' }}">
                    Semua Kategori
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('katalog', ['kategori' => $cat->slug, 'cari' => $search]) }}" class="px-4 py-2 rounded-xl font-semibold whitespace-nowrap transition {{ $selectedCategory === $cat->slug ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>

            <!-- Search Form -->
            <form action="{{ route('katalog') }}" method="GET" class="relative min-w-[280px]">
                @if($selectedCategory)
                    <input type="hidden" name="kategori" value="{{ $selectedCategory }}">
                @endif
                <input type="text" name="cari" value="{{ $search }}" placeholder="Cari botol, kardus, tembaga..." class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 shadow-sm">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-xs"></i>
            </form>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if($items->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($items as $item)
                <div class="p-6 rounded-3xl bg-white border border-slate-200/80 hover:border-emerald-400 hover:shadow-xl hover:shadow-emerald-500/10 transition group flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                {{ $item->category->name }}
                            </span>
                            <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-lg flex items-center gap-1">
                                <i class="fa-solid fa-star text-[10px]"></i> +{{ $item->points_per_unit }} Poin
                            </span>
                        </div>

                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-emerald-700 transition">
                            {{ $item->name }}
                        </h3>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                            {{ $item->description ?? 'Sampah dipilah dalam kondisi kering, bersih dari tanah, dan dikemas rapi.' }}
                        </p>

                        <div class="mt-4 flex items-center gap-2 text-xs text-slate-400">
                            <i class="fa-solid fa-leaf text-emerald-500"></i>
                            <span>Reduksi CO₂: ~{{ $item->co2_factor_per_unit }} kg CO₂e / {{ $item->unit }}</span>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <div>
                            <span class="text-[11px] text-slate-400 block font-medium">Harga Pembelian</span>
                            <span class="text-2xl font-extrabold text-emerald-600">
                                Rp {{ number_format($item->buy_price_per_unit, 0, ',', '.') }}
                            </span>
                            <span class="text-xs text-slate-400 font-medium">/ {{ $item->unit }}</span>
                        </div>

                        <a href="{{ route('kalkulator') }}" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white transition">
                            <i class="fa-solid fa-calculator"></i> Hitung
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $items->links() }}
        </div>
    @else
        <div class="text-center py-16 bg-white rounded-3xl border border-slate-200">
            <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto text-2xl mb-4">
                <i class="fa-solid fa-box-open"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800">Sampah Tidak Ditemukan</h3>
            <p class="text-sm text-slate-500 mt-1">Coba gunakan kata kunci pencarian lain atau pilih kategori yang berbeda.</p>
            <a href="{{ route('katalog') }}" class="inline-block mt-4 px-4 py-2 rounded-xl text-xs font-bold text-emerald-600 bg-emerald-50 hover:bg-emerald-100 transition">
                Reset Filter
            </a>
        </div>
    @endif
</div>
@endsection
