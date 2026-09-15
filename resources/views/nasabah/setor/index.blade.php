@extends('layouts.app')

@section('title', 'Fitur 2: Melihat Riwayat Setoran - Nasabah')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 mb-2">
                <i class="fa-solid fa-clock-rotate-left"></i> Fitur 2 Nasabah: Melihat Riwayat Setoran
            </span>
            <h1 class="text-2xl font-extrabold text-slate-900">Riwayat Setoran Sampah Nasabah</h1>
            <p class="text-xs text-slate-500 mt-1">
                Sistem menampilkan riwayat setoran sampah yang dilakukan nasabah, meliputi tanggal setoran, jenis sampah, berat sampah, dan nilai rupiah hasil konversi.
            </p>
        </div>
        <a href="{{ route('nasabah.setor.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-bold text-xs text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 shadow-md shadow-emerald-600/20 transition">
            <i class="fa-solid fa-plus-circle"></i> Ajukan Setor Sampah Baru
        </a>
    </div>

    <!-- Tabel Riwayat Setoran Sesuai Konstruksi -->
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        @if($deposits->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[11px] border-b border-slate-100">
                        <tr>
                            <th class="py-4 px-6">Tanggal Setoran</th>
                            <th class="py-4 px-6">Kode Transaksi</th>
                            <th class="py-4 px-6">Jenis Sampah yang Disetor</th>
                            <th class="py-4 px-6">Berat Sampah (kg)</th>
                            <th class="py-4 px-6">Nilai Rupiah Hasil Konversi</th>
                            <th class="py-4 px-6">Status</th>
                            <th class="py-4 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($deposits as $deposit)
                            <tr class="hover:bg-slate-50/80 transition">
                                <!-- Tanggal Setoran -->
                                <td class="py-4 px-6 font-medium text-slate-800">
                                    {{ $deposit->created_at->format('d M Y, H:i') }} WIB
                                </td>

                                <!-- Kode Transaksi -->
                                <td class="py-4 px-6 font-mono font-bold text-slate-700">
                                    {{ $deposit->code }}
                                </td>

                                <!-- Jenis Sampah -->
                                <td class="py-4 px-6">
                                    @if($deposit->details->count() > 0)
                                        <div class="space-y-1">
                                            @foreach($deposit->details as $det)
                                                <div class="flex items-center gap-1.5">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                    <span class="font-bold text-slate-800">{{ $det->item->name ?? 'Sampah' }}</span>
                                                    <span class="text-slate-400">({{ $det->weight }} {{ $det->item->unit ?? 'kg' }} @ Rp {{ number_format($det->price_per_unit, 0, ',', '.') }})</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-slate-400 italic">{{ $deposit->notes ?? 'Belum ditimbang rincian' }}</span>
                                    @endif
                                </td>

                                <!-- Berat Sampah -->
                                <td class="py-4 px-6 font-extrabold text-slate-900 text-sm">
                                    {{ $deposit->total_weight_kg }} kg
                                </td>

                                <!-- Nilai Rupiah Hasil Konversi -->
                                <td class="py-4 px-6 font-extrabold text-emerald-600 text-sm">
                                    Rp {{ number_format($deposit->total_amount, 0, ',', '.') }}
                                </td>

                                <!-- Status -->
                                <td class="py-4 px-6">
                                    @if($deposit->status === 'completed')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800">
                                            <i class="fa-solid fa-circle-check text-[9px]"></i> Selesai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-100 text-amber-800">
                                            <i class="fa-solid fa-clock text-[9px]"></i> Menunggu Timbang
                                        </span>
                                    @endif
                                </td>

                                <!-- Aksi -->
                                <td class="py-4 px-6 text-right">
                                    <a href="{{ route('nasabah.setor.show', $deposit->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 text-slate-700 font-bold transition">
                                        <i class="fa-solid fa-receipt"></i> Slip Rincian
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $deposits->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <i class="fa-solid fa-recycle text-4xl text-slate-300 mb-3 block"></i>
                <h3 class="text-base font-bold text-slate-800">Belum Ada Riwayat Setoran</h3>
                <p class="text-xs text-slate-400 mt-1">Anda belum memiliki catatan setoran sampah daur ulang.</p>
                <a href="{{ route('nasabah.setor.create') }}" class="inline-block mt-4 px-4 py-2 rounded-xl text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 transition">
                    Setor Sampah Sekarang
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
