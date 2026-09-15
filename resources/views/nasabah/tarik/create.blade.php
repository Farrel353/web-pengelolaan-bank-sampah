@extends('layouts.app')

@section('title', 'Ajukan Penarikan / Tukar Poin - EcoBank')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">
    <div>
        <a href="{{ route('nasabah.tarik.index') }}" class="text-xs font-semibold text-slate-500 hover:text-emerald-600 flex items-center gap-1 mb-1">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Riwayat Penarikan
        </a>
        <h1 class="text-2xl font-extrabold text-slate-900">Formulir Pencairan Dana & Reward</h1>
        <p class="text-xs text-slate-500">Cairkan saldo hasil penjualan sampah atau tukar eco-points dengan sembako.</p>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm" x-data="{ tab: 'cash', channel: 'GoPay' }">
        <!-- Tab Selector -->
        <div class="flex items-center gap-2 p-1.5 rounded-2xl bg-slate-100 mb-6 text-xs font-bold">
            <button type="button" @click="tab = 'cash'" class="flex-1 py-2.5 rounded-xl transition flex items-center justify-center gap-2" :class="tab === 'cash' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-500 hover:text-slate-800'">
                <i class="fa-solid fa-money-bill-wave"></i> Tarik Saldo Rupiah
            </button>
            <button type="button" @click="tab = 'reward'" class="flex-1 py-2.5 rounded-xl transition flex items-center justify-center gap-2" :class="tab === 'reward' ? 'bg-white text-amber-700 shadow-sm' : 'text-slate-500 hover:text-slate-800'">
                <i class="fa-solid fa-gift"></i> Tukar Poin Reward (Sembako)
            </button>
        </div>

        <!-- Form 1: Tarik Saldo Rupiah -->
        <div x-show="tab === 'cash'">
            <div class="mb-5 p-4 rounded-2xl bg-emerald-50 border border-emerald-200/80 flex items-center justify-between text-xs">
                <span class="text-emerald-800">Saldo tabungan Anda saat ini:</span>
                <strong class="text-base text-emerald-800 font-extrabold">Rp {{ number_format($profile->balance, 0, ',', '.') }}</strong>
            </div>

            <form action="{{ route('nasabah.tarik.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="type" value="ewallet">

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Pilih Dompet Digital / Bank Tujuan</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs">
                        @foreach(['GoPay', 'OVO', 'DANA', 'ShopeePay', 'Bank BCA', 'Bank Mandiri', 'Bank BRI', 'Bank BNI'] as $opt)
                            <label class="p-3 rounded-xl border cursor-pointer text-center font-bold transition flex items-center justify-center gap-1.5" :class="channel === '{{ $opt }}' ? 'border-emerald-500 bg-emerald-50 text-emerald-800' : 'border-slate-200 text-slate-700 hover:bg-slate-50'">
                                <input type="radio" name="channel" value="{{ $opt }}" x-model="channel" class="sr-only">
                                <span>{{ $opt }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor Rekening / No. HP Akun</label>
                        <input type="text" name="account_number" value="{{ $user->phone }}" required placeholder="Contoh: 0812xxxx atau no rek" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Pemilik Akun</label>
                        <input type="text" name="account_holder_name" value="{{ $user->name }}" required placeholder="Sesuai buku tabungan/e-wallet" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nominal Penarikan (Rp)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-2.5 text-xs text-slate-400 font-bold">Rp</span>
                        <input type="number" name="amount" min="10000" max="{{ $profile->balance }}" step="1000" placeholder="Minimal Rp 10.000" required class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 font-bold text-slate-800">
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Minimal penarikan adalah Rp 10.000. Proses transfer 1x24 jam kerja.</p>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold text-xs shadow-lg shadow-emerald-600/25 transition">
                        Kirim Permohonan Pencairan
                    </button>
                </div>
            </form>
        </div>

        <!-- Form 2: Tukar Poin Reward Sembako -->
        <div x-show="tab === 'reward'" x-cloak>
            <div class="mb-5 p-4 rounded-2xl bg-amber-50 border border-amber-200/80 flex items-center justify-between text-xs">
                <span class="text-amber-800">Eco-Points Anda saat ini:</span>
                <strong class="text-base text-amber-800 font-extrabold">{{ number_format($profile->points, 0, ',', '.') }} Poin</strong>
            </div>

            <form action="{{ route('nasabah.tarik.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="type" value="reward_goods">

                <div class="space-y-3">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Pilih Paket Hadiah / Sembako</label>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="p-4 rounded-2xl border-2 border-slate-200 hover:border-amber-400 cursor-pointer transition flex items-start gap-3">
                            <input type="radio" name="channel" value="Minyak Goreng 1 Liter" checked class="mt-1 text-amber-600 focus:ring-amber-500">
                            <div>
                                <strong class="block text-xs sm:text-sm text-slate-800">Minyak Goreng 1 Liter</strong>
                                <span class="text-xs text-amber-600 font-bold">500 Poin</span>
                                <p class="text-[11px] text-slate-400 mt-1">Minyak kelapa sawit higienis pouch 1L.</p>
                            </div>
                        </label>

                        <label class="p-4 rounded-2xl border-2 border-slate-200 hover:border-amber-400 cursor-pointer transition flex items-start gap-3">
                            <input type="radio" name="channel" value="Beras Premium 2.5 kg" class="mt-1 text-amber-600 focus:ring-amber-500">
                            <div>
                                <strong class="block text-xs sm:text-sm text-slate-800">Beras Premium 2.5 kg</strong>
                                <span class="text-xs text-amber-600 font-bold">800 Poin</span>
                                <p class="text-[11px] text-slate-400 mt-1">Beras pulen kemasan premium 2.5 kg.</p>
                            </div>
                        </label>

                        <label class="p-4 rounded-2xl border-2 border-slate-200 hover:border-amber-400 cursor-pointer transition flex items-start gap-3">
                            <input type="radio" name="channel" value="Gula Pasir 1 kg" class="mt-1 text-amber-600 focus:ring-amber-500">
                            <div>
                                <strong class="block text-xs sm:text-sm text-slate-800">Gula Pasir 1 kg</strong>
                                <span class="text-xs text-amber-600 font-bold">400 Poin</span>
                                <p class="text-[11px] text-slate-400 mt-1">Gula tebu murni kemasan 1 kg.</p>
                            </div>
                        </label>

                        <label class="p-4 rounded-2xl border-2 border-slate-200 hover:border-amber-400 cursor-pointer transition flex items-start gap-3">
                            <input type="radio" name="channel" value="Bibit Pohon Buah & Pupuk Kompos" class="mt-1 text-amber-600 focus:ring-amber-500">
                            <div>
                                <strong class="block text-xs sm:text-sm text-slate-800">Bibit Pohon Buah & Kompos</strong>
                                <span class="text-xs text-amber-600 font-bold">300 Poin</span>
                                <p class="text-[11px] text-slate-400 mt-1">Bibit mangga/jambu + 2kg kompos organik.</p>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-bold text-xs shadow-lg shadow-amber-500/25 transition">
                        Klaim Hadiah dengan Poin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
