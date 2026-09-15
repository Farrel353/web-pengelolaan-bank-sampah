@extends('layouts.app')

@section('title', 'Struk Transaksi Penimbangan #' . $deposit->code . ' - EcoBank')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">
    <div class="flex items-center justify-between no-print">
        <a href="{{ route('admin.timbang.index') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1.5">
            <i class="fa-solid fa-plus"></i> Timbang Nasabah Lainnya
        </a>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs flex items-center gap-2 shadow-sm transition">
                <i class="fa-solid fa-print"></i> Cetak Struk
            </button>
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-xs transition">
                Dashboard
            </a>
        </div>
    </div>

    <!-- Official Thermal-style or Standard Receipt Slip -->
    <div id="printable-receipt" class="bg-white p-8 rounded-3xl border border-slate-200 shadow-xl space-y-6 text-slate-800">
        <!-- Receipt Header -->
        <div class="text-center pb-4 border-b-2 border-dashed border-slate-200 space-y-1">
            <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-xl mx-auto mb-2 shadow-md">
                <i class="fa-solid fa-recycle"></i>
            </div>
            <h2 class="text-xl font-black tracking-tight text-slate-900">EcoBank Bank Sampah</h2>
            <p class="text-[11px] text-slate-500">Jl. Hijau Lestari No. 88 &bull; Telp: (021) 8899-2345</p>
            <p class="text-[10px] text-slate-400 font-mono mt-1">STRUK RESMI PENIMBANGAN SAMPAH</p>
        </div>

        <!-- Meta Grid -->
        <div class="grid grid-cols-2 gap-2 text-xs py-1 border-b border-slate-100">
            <div>
                <span class="text-slate-400 block text-[10px]">No. Struk</span>
                <strong class="font-mono">{{ $deposit->code }}</strong>
            </div>
            <div class="text-right">
                <span class="text-slate-400 block text-[10px]">Waktu Transaksi</span>
                <strong>{{ $deposit->created_at->format('d/m/Y H:i') }} WIB</strong>
            </div>
            <div class="mt-2">
                <span class="text-slate-400 block text-[10px]">Nasabah</span>
                <strong class="text-emerald-700">{{ $deposit->user->name }}</strong>
            </div>
            <div class="mt-2 text-right">
                <span class="text-slate-400 block text-[10px]">No. Rekening</span>
                <strong class="font-mono text-slate-600">{{ $deposit->user->nasabahProfile?->no_rekening ?? '-' }}</strong>
            </div>
        </div>

        <!-- Items Table -->
        <div>
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-[10px] text-slate-400 uppercase">
                        <th class="py-2">Item Sampah</th>
                        <th class="py-2 text-center">Berat</th>
                        <th class="py-2 text-right">Tarif</th>
                        <th class="py-2 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($deposit->details as $d)
                        <tr class="py-2">
                            <td class="py-2">
                                <span class="font-bold text-slate-800 block">{{ $d->item->name }}</span>
                                <span class="text-[10px] text-amber-600">+{{ $d->subtotal_points }} Poin</span>
                            </td>
                            <td class="py-2 text-center font-medium">{{ $d->weight }} {{ $d->item->unit }}</td>
                            <td class="py-2 text-right text-slate-500">Rp {{ number_format($d->price_per_unit, 0, ',', '.') }}</td>
                            <td class="py-2 text-right font-bold text-slate-900">Rp {{ number_format($d->subtotal_amount, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Total Breakdown -->
        <div class="pt-3 border-t-2 border-dashed border-slate-200 space-y-1.5 text-xs">
            <div class="flex justify-between text-slate-600">
                <span>Total Berat Sampah:</span>
                <strong class="text-slate-800">{{ $deposit->total_weight_kg }} kg</strong>
            </div>
            <div class="flex justify-between text-slate-600">
                <span>Bonus Eco-Points:</span>
                <strong class="text-amber-600">+{{ $deposit->total_points }} Pts</strong>
            </div>
            <div class="flex justify-between text-slate-600">
                <span>Reduksi Emisi Karbon:</span>
                <strong class="text-emerald-600">~{{ $deposit->total_co2_kg }} kg CO₂e</strong>
            </div>
            <div class="flex justify-between text-base font-extrabold text-emerald-800 pt-2 border-t border-slate-200">
                <span>SALDO DITAMBAHKAN:</span>
                <span>Rp {{ number_format($deposit->total_amount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-xs text-slate-500 pt-1">
                <span>Saldo Akhir Nasabah:</span>
                <span class="font-bold font-mono">Rp {{ number_format($deposit->user->nasabahProfile?->balance, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Footer Notice & Signature -->
        <div class="pt-4 border-t border-slate-100 text-center text-[10px] text-slate-400 space-y-2">
            <p>Petugas Kasir: {{ $deposit->officer->name ?? 'Admin Bank Sampah' }}</p>
            <p class="font-medium text-emerald-600">Terima kasih telah berkontribusi merawat kelestarian lingkungan hidup!</p>
            <p>Simpan struk ini sebagai bukti transaksi yang sah.</p>
        </div>
    </div>
</div>
@endsection
