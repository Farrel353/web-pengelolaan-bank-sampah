@extends('layouts.app')

@section('title', 'Fitur 4: Melihat Harga Sampah - Nasabah')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 mb-2">
                <i class="fa-solid fa-tags"></i> Fitur 4 Nasabah: Melihat Harga Sampah
            </span>
            <h1 class="text-2xl font-extrabold text-slate-900">Daftar Harga Sampah yang Berlaku</h1>
            <p class="text-xs text-slate-500 mt-1">
                Daftar komoditas jenis sampah beserta harga beli per kilogram (Rp/kg) yang sedang berlaku di Bank Sampah.
            </p>
        </div>
        <a href="{{ route('nasabah.dashboard') }}" class="text-xs font-bold text-slate-600 hover:text-emerald-700 flex items-center gap-1">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row gap-4 items-center justify-between">
        <!-- Category Filter Chips -->
        <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto text-xs pb-1 md:pb-0">
            <a href="{{ route('nasabah.harga') }}" class="px-3.5 py-2 rounded-xl font-bold whitespace-nowrap transition {{ empty($selectedCategory) ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                Semua Kategori
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('nasabah.harga', ['kategori' => $cat->slug, 'cari' => $search]) }}" class="px-3.5 py-2 rounded-xl font-bold whitespace-nowrap transition {{ $selectedCategory === $cat->slug ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>

        <!-- Search Input -->
        <form action="{{ route('nasabah.harga') }}" method="GET" class="relative w-full md:w-72">
            @if($selectedCategory)
                <input type="hidden" name="kategori" value="{{ $selectedCategory }}">
            @endif
            <input type="text" name="cari" value="{{ $search }}" placeholder="Cari jenis sampah..." class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
        </form>
    </div>

    <!-- Price Table (Daftar Jenis Sampah & Harga per kg) -->
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[11px] border-b border-slate-100">
                    <tr>
                        <th class="py-4 px-6">Jenis Sampah</th>
                        <th class="py-4 px-6">Kategori</th>
                        <th class="py-4 px-6">Satuan</th>
                        <th class="py-4 px-6">Harga per Kilogram (Rp)</th>
                        <th class="py-4 px-6">Keterangan / Kondisi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($items as $item)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-4 px-6">
                                <strong class="text-sm font-bold text-slate-900 block">{{ $item->name }}</strong>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                    {{ $item->category->name }}
                                </span>
                            </td>
                            <td class="py-4 px-6 font-semibold text-slate-700">
                                {{ strtoupper($item->unit) }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="text-base font-extrabold text-emerald-600">
                                    Rp {{ number_format($item->buy_price_per_unit, 0, ',', '.') }}
                                </span>
                                <span class="text-xs text-slate-400 font-normal">/ {{ $item->unit }}</span>
                            </td>
                            <td class="py-4 px-6 text-slate-500 text-xs max-w-sm">
                                {{ $item->description ?? 'Bersih, kering, dan dipisahkan sesuai jenis.' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                Tidak ada jenis sampah yang cocok dengan pencarian Anda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
