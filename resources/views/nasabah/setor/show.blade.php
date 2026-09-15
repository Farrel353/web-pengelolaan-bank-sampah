@extends('layouts.app')

@section('title', 'Slip Struk Setor Sampah #' . $deposit->code . ' - EcoBank')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">
    <!-- Top Action Bar -->
    <div class="flex items-center justify-between no-print">
        <a href="{{ route('nasabah.setor.index') }}" class="text-xs font-semibold text-slate-500 hover:text-emerald-600 flex items-center gap-1">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Riwayat
        </a>
        <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs flex items-center gap-2 shadow-sm transition">
            <i class="fa-solid fa-print"></i> Cetak Struk (Print)
        </button>
    </div>

    <!-- Printable Receipt Slip Card -->
    <div id="printable-receipt" class="bg-white p-8 sm:p-10 rounded-3xl border border-slate-200 shadow-xl space-y-6">
        <!-- Receipt Header -->
        <div class="flex items-center justify-between pb-6 border-b-2 border-dashed border-slate-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 text-white flex items-center justify-center text-xl shadow-md">
                    <i class="fa-solid fa-recycle"></i>
                </div>
                <div>
                    <h2 class="text-xl font-extrabold text-slate-900">EcoBank</h2>
                    <p class="text-xs text-slate-400">Bukti Penimbangan & Setoran Sampah Resmi</p>
                </div>
            </div>
            <div class="text-right">
                <span class="inline-block px-3 py-1 rounded-full text-[10px] font-extrabold uppercase {{ $deposit->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                    {{ $deposit->status === 'completed' ? 'SELESAI / SALDO DIKREDITKAN' : 'MENUNGGU TIMBANG' }}
                </span>
                <p class="font-mono text-xs font-bold text-slate-700 mt-1">{{ $deposit->code }}</p>
            </div>
        </div>

        <!-- Meta Information -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs py-2">
            <div>
                <span class="text-slate-400 block font-medium">Tanggal Transaksi</span>
                <strong class="text-slate-800">{{ $deposit->created_at->format('d/m/Y H:i') }} WIB</strong>
            </div>
            <div>
                <span class="text-slate-400 block font-medium">Nama Nasabah</span>
                <strong class="text-slate-800">{{ $deposit->user->name }}</strong>
            </div>
            <div>
                <span class="text-slate-400 block font-medium">No. Rekening</span>
                <strong class="text-slate-800 font-mono">{{ $deposit->user->nasabahProfile?->no_rekening ?? '-' }}</strong>
            </div>
            <div>
                <span class="text-slate-400 block font-medium">Metode</span>
                <strong class="text-slate-800 capitalize">{{ $deposit->deposit_type === 'pickup' ? 'Jemput Rumah' : 'Drop-off Mandiri' }}</strong>
            </div>
        </div>

        <!-- Details Table -->
        <div class="border border-slate-200 rounded-2xl overflow-hidden">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[10px] border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Komoditas Sampah</th>
                        <th class="py-3 px-4 text-center">Bobot</th>
                        <th class="py-3 px-4 text-right">Harga Satuan</th>
                        <th class="py-3 px-4 text-right">Poin</th>
                        <th class="py-3 px-4 text-right">Subtotal Rupiah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($deposit->details as $detail)
                        <tr>
                            <td class="py-3.5 px-4">
                                <strong class="text-slate-800 block">{{ $detail->item->name }}</strong>
                                <span class="text-[10px] text-slate-400">{{ $detail->item->category->name }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-center font-semibold text-slate-700">
                                {{ $detail->weight }} {{ $detail->item->unit }}
                            </td>
                            <td class="py-3.5 px-4 text-right text-slate-600">
                                Rp {{ number_format($detail->price_per_unit, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-right text-amber-600 font-bold">
                                +{{ $detail->subtotal_points }}
                            </td>
                            <td class="py-3.5 px-4 text-right font-bold text-emerald-700">
                                Rp {{ number_format($detail->subtotal_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-slate-400">
                                Belum ada rincian timbangan. Menunggu penimbangan oleh petugas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-slate-50/80 font-bold border-t border-slate-200">
                    <tr>
                        <td class="py-3.5 px-4 text-slate-700">TOTAL HASIL TIMBANG</td>
                        <td class="py-3.5 px-4 text-center text-slate-800">{{ $deposit->total_weight_kg }} kg</td>
                        <td class="py-3.5 px-4"></td>
                        <td class="py-3.5 px-4 text-right text-amber-600">+{{ $deposit->total_points }} pts</td>
                        <td class="py-3.5 px-4 text-right text-base text-emerald-700">
                            Rp {{ number_format($deposit->total_amount, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Environmental Impact & Officer Note -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 text-xs">
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200/80 text-emerald-800">
                <div class="flex items-center gap-2 font-bold mb-1">
                    <i class="fa-solid fa-leaf text-emerald-600"></i> Kontribusi Lingkungan Hidup
                </div>
                <p class="text-[11px] leading-relaxed">
                    Setoran ini berhasil mereduksi estimasi emisi setara <strong>{{ $deposit->total_co2_kg }} kg CO₂e</strong> dari pencemaran lingkungan.
                </p>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-slate-600">
                <div class="font-bold text-slate-800 mb-1">Petugas Penimbang</div>
                <p class="text-[11px]">{{ $deposit->officer->name ?? 'Petugas Lapangan' }}</p>
                <p class="text-[10px] text-slate-400 mt-1">Struk ini sah diterbitkan oleh sistem EcoBank tanpa stempel basah.</p>
            </div>
        </div>
    </div>
</div>
@endsection
