@extends('layouts.app')

@section('title', 'Ajukan Setor Sampah - EcoBank')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('nasabah.setor.index') }}" class="text-xs font-semibold text-slate-500 hover:text-emerald-600 flex items-center gap-1 mb-1">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Riwayat Setor
            </a>
            <h1 class="text-2xl font-extrabold text-slate-900">Formulir Setor Sampah</h1>
            <p class="text-xs text-slate-500">Pilih metode penyetoran dan jadwalkan penimbangan sampah Anda.</p>
        </div>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm" x-data="{ depositType: 'pickup' }">
        <form action="{{ route('nasabah.setor.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Pilihan Metode Setoran -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Metode Penyetoran</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label class="p-4 rounded-2xl border-2 cursor-pointer transition flex items-center gap-3" :class="depositType === 'pickup' ? 'border-emerald-500 bg-emerald-50/50' : 'border-slate-200 hover:border-slate-300'">
                        <input type="radio" name="deposit_type" value="pickup" x-model="depositType" class="text-emerald-600 focus:ring-emerald-500">
                        <div>
                            <span class="block font-bold text-xs sm:text-sm text-slate-800">
                                <i class="fa-solid fa-truck-pickup text-emerald-600 mr-1"></i> Jemput ke Rumah
                            </span>
                            <span class="block text-[11px] text-slate-500">Petugas mengambil ke alamat Anda</span>
                        </div>
                    </label>

                    <label class="p-4 rounded-2xl border-2 cursor-pointer transition flex items-center gap-3" :class="depositType === 'drop_off' ? 'border-emerald-500 bg-emerald-50/50' : 'border-slate-200 hover:border-slate-300'">
                        <input type="radio" name="deposit_type" value="drop_off" x-model="depositType" class="text-emerald-600 focus:ring-emerald-500">
                        <div>
                            <span class="block font-bold text-xs sm:text-sm text-slate-800">
                                <i class="fa-solid fa-box-archive text-teal-600 mr-1"></i> Antar Mandiri (Drop-off)
                            </span>
                            <span class="block text-[11px] text-slate-500">Bawa langsung ke titik bank sampah</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Tanggal & Waktu -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tanggal Pelaksanaan</label>
                    <input type="date" name="pickup_date" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Pilihan Waktu (Slot)</label>
                    <select name="pickup_time_slot" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="Pagi (08:30 - 11:30 WIB)">Pagi (08:30 - 11:30 WIB)</option>
                        <option value="Siang (13:00 - 15:30 WIB)">Siang (13:00 - 15:30 WIB)</option>
                    </select>
                </div>
            </div>

            <!-- Alamat Jemput (Hanya muncul jika pickup) -->
            <div x-show="depositType === 'pickup'" x-transition>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Lengkap Penjemputan</label>
                <textarea name="pickup_address" rows="2" placeholder="Masukkan alamat lengkap rumah dan patokan..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">{{ $user->nasabahProfile?->address }}</textarea>
            </div>

            <!-- Estimasi Berat -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Perkiraan Berat Total (kg)</label>
                <div class="relative">
                    <input type="number" step="0.5" min="0.5" name="estimated_weight_kg" value="5" placeholder="Contoh: 10" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <span class="absolute right-4 top-2.5 text-xs text-slate-400 font-bold">kg</span>
                </div>
                <p class="text-[11px] text-slate-400 mt-1">Berat final akan diukur akurat dengan timbangan digital oleh petugas.</p>
            </div>

            <!-- Catatan Tambahan -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Catatan Tambahan (Opsional)</label>
                <input type="text" name="notes" placeholder="Contoh: Kardus 2 ikat besar dan botol mineral 1 karung" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('nasabah.setor.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-xs transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold text-xs shadow-lg shadow-emerald-600/25 transition">
                    Kirim Permohonan Setor Sampah
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
