@extends('layouts.app')

@section('title', 'Fitur 3: Mencatat Setoran Sampah - Pengurus')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6" x-data="timbangPOS()">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 mb-2">
                <i class="fa-solid fa-scale-balanced"></i> Fitur 3 Pengurus: Mencatat Setoran Sampah
            </span>
            <h1 class="text-2xl font-extrabold text-slate-900 mt-0.5">Pencatatan Setoran Sampah Nasabah</h1>
            <p class="text-xs text-slate-500">
                Pengurus mencatat jenis dan berat sampah yang disetorkan nasabah. Sistem menghitung nilai setoran berdasarkan berat sampah dan harga per kilogram, kemudian menambahkan hasilnya ke saldo nasabah.
            </p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900 flex items-center gap-1">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    @if($pendingDeposits->count() > 0)
        <!-- Antrean Pengajuan Setor/Jemput Nasabah -->
        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-xs text-amber-900 space-y-2">
            <div class="font-bold flex items-center gap-2">
                <i class="fa-solid fa-bell text-amber-600"></i> Ada {{ $pendingDeposits->count() }} Permohonan Setor yang Belum Ditimbang:
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach($pendingDeposits as $pd)
                    <button type="button" @click="loadPending('{{ $pd->id }}', '{{ $pd->user_id }}', '{{ $pd->code }}', '{{ $pd->deposit_type }}')" class="px-3 py-1.5 rounded-xl bg-white border border-amber-300 hover:bg-amber-600 hover:text-white transition font-medium text-[11px] flex items-center gap-1.5 shadow-sm">
                        <i class="fa-solid fa-user"></i> {{ $pd->user->name }} ({{ $pd->code }})
                    </button>
                @endforeach
            </div>
        </div>
    @endif

    <form action="{{ route('admin.timbang.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        @csrf
        <input type="hidden" name="pending_deposit_id" :value="pendingDepositId">

        <!-- Form Pencatatan Setoran -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- Pilih Nasabah -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h2 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">Langkah 1: Pilih Rekening Nasabah</h2>
                    <a href="{{ route('admin.nasabah.index') }}" class="text-xs font-bold text-emerald-600 hover:underline">
                        + Tambah Nasabah
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama & No. Rekening Nasabah</label>
                        <select name="user_id" x-model="selectedUserId" @change="updateNasabahInfo()" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs sm:text-sm font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="">-- Pilih Nasabah --</option>
                            @foreach($nasabahs as $n)
                                <option value="{{ $n->id }}" data-rekening="{{ $n->nasabahProfile?->no_rekening }}" data-saldo="{{ $n->nasabahProfile?->balance }}">
                                    {{ $n->name }} ({{ $n->nasabahProfile?->no_rekening ?? 'No Rek -' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Metode Penyetoran</label>
                        <select name="deposit_type" x-model="depositType" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="drop_off">Antar Mandiri (Drop-off di Bank Sampah)</option>
                            <option value="pickup">Layanan Jemput (Pickup Rumah)</option>
                        </select>
                    </div>
                </div>

                <!-- Info Saldo Nasabah Sebelum Ditambah -->
                <div x-show="selectedUserId" class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center justify-between text-xs">
                    <div>
                        <span class="text-slate-400 block font-mono" x-text="'No. Rekening: ' + currentNasabahRekening"></span>
                        <strong class="text-slate-800 text-sm">Nasabah Terverifikasi</strong>
                    </div>
                    <div class="text-right">
                        <span class="text-slate-400 block">Saldo Sebelum Setor</span>
                        <strong class="text-slate-800 font-bold text-sm" x-text="'Rp ' + formatRupiah(currentNasabahSaldo)"></strong>
                    </div>
                </div>
            </div>

            <!-- Input Jenis & Berat Sampah -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h2 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">Langkah 2: Catat Jenis dan Berat Sampah</h2>
                        <span class="text-[11px] text-slate-400">Rumus: Nilai Setoran = Berat Sampah (kg) &times; Harga per kg</span>
                    </div>
                    <button type="button" @click="addRow()" class="px-3 py-1.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 font-bold text-xs transition flex items-center gap-1.5">
                        <i class="fa-solid fa-plus"></i> Tambah Baris Sampah
                    </button>
                </div>

                <div class="space-y-3">
                    <template x-for="(row, idx) in rows" :key="idx">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-500 font-mono" x-text="'Komoditas #' + (idx + 1)"></span>
                                <button type="button" x-show="rows.length > 1" @click="removeRow(idx)" class="text-xs text-rose-500 hover:text-rose-700 font-semibold flex items-center gap-1">
                                    <i class="fa-solid fa-trash-can"></i> Hapus
                                </button>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                                <!-- Pilih Jenis Sampah -->
                                <div class="sm:col-span-7">
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Jenis Sampah</label>
                                    <select :name="'items[' + idx + '][waste_item_id]'" x-model="row.itemId" @change="updateRowItem(idx)" required class="w-full text-xs sm:text-sm rounded-xl border border-slate-200 bg-white px-3 py-2.5 focus:ring-2 focus:ring-amber-500">
                                        <template x-for="item in wasteList" :key="item.id">
                                            <option :value="item.id" x-text="item.name + ' - (Rp ' + formatRupiah(item.price) + '/' + item.unit + ')'"></option>
                                        </template>
                                    </select>
                                </div>

                                <!-- Input Berat Sampah -->
                                <div class="sm:col-span-5">
                                    <label class="block text-xs font-medium text-slate-600 mb-1">
                                        Berat Sampah (<span x-text="row.unit"></span>)
                                    </label>
                                    <div class="relative">
                                        <input type="number" step="0.1" min="0.05" :name="'items[' + idx + '][weight]'" x-model.number="row.weight" required placeholder="0.0" class="w-full text-xs sm:text-sm font-bold text-slate-900 rounded-xl border border-slate-200 bg-white px-3 py-2.5 pr-12 focus:ring-2 focus:ring-amber-500">
                                        <span class="absolute right-3 top-2.5 text-xs text-slate-400 font-bold" x-text="row.unit"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Perhitungan Nilai Setoran per Baris -->
                            <div class="flex items-center justify-between pt-2 border-t border-slate-200/60 text-xs">
                                <span class="text-slate-500">
                                    Nilai Setoran: <span class="font-mono text-slate-400" x-text="row.weight + ' ' + row.unit + ' &times; Rp ' + formatRupiah(row.price)"></span>
                                </span>
                                <strong class="text-emerald-700 font-extrabold text-sm" x-text="'= Rp ' + formatRupiah(row.weight * row.price)"></strong>
                            </div>
                        </div>
                    </template>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Catatan Tambahan (Opsional)</label>
                    <input type="text" name="notes" placeholder="Contoh: Kondisi sampah bersih, kering, disortir rapi." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

        </div>

        <!-- Right Column: Ringkasan Nilai Setoran & Tombol Simpan -->
        <div class="lg:col-span-4 sticky top-24">
            <div class="p-6 rounded-3xl bg-slate-900 text-white shadow-2xl space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-white/10">
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-amber-400">Hasil Hitungan Nilai Setoran</span>
                        <h3 class="text-base font-bold">Ringkasan Penambahan Saldo</h3>
                    </div>
                    <div class="w-8 h-8 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-calculator"></i>
                    </div>
                </div>

                <!-- Nilai Setoran yang Akan Ditambahkan -->
                <div class="p-5 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15">
                    <span class="text-xs text-amber-300 block font-semibold">Total Nilai Setoran</span>
                    <p class="text-3xl font-black text-white mt-1">
                        Rp <span x-text="formatRupiah(totalAmount)"></span>
                    </p>
                    <p class="text-[11px] text-emerald-300 mt-1">
                        <i class="fa-solid fa-arrow-down mr-1"></i> Otomatis menambahkan hasilnya ke saldo nasabah
                    </p>
                </div>

                <!-- Total Bobot & Saldo Akhir -->
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between py-2 border-b border-white/10 text-slate-300">
                        <span>Total Berat Sampah:</span>
                        <strong class="text-white" x-text="totalWeight.toFixed(1) + ' kg'"></strong>
                    </div>
                    <div class="flex justify-between py-2 border-b border-white/10 text-slate-300">
                        <span>Estimasi Saldo Akhir Nasabah:</span>
                        <strong class="text-emerald-400 font-mono" x-text="'Rp ' + formatRupiah(currentNasabahSaldo + totalAmount)"></strong>
                    </div>
                </div>

                <!-- Tombol Simpan -->
                <div class="pt-2">
                    <button type="submit" :disabled="!selectedUserId || totalAmount <= 0" class="w-full py-3.5 px-4 rounded-2xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 disabled:opacity-50 disabled:cursor-not-allowed text-white font-extrabold text-xs uppercase tracking-wider text-center block shadow-lg shadow-amber-500/30 transition active:scale-95 cursor-pointer">
                        <i class="fa-solid fa-circle-check mr-1"></i> Simpan & Tambahkan ke Saldo
                    </button>
                    <p class="text-[10px] text-slate-400 text-center mt-2">Sistem langsung memperbarui saldo nasabah seketika.</p>
                </div>
            </div>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
function timbangPOS() {
    const rawList = @json($items);

    return {
        wasteList: rawList.map(i => ({
            id: i.id,
            name: i.name,
            unit: i.unit,
            price: parseFloat(i.buy_price_per_unit),
            points: parseInt(i.points_per_unit || 0),
            co2: parseFloat(i.co2_factor_per_unit || 0)
        })),

        selectedUserId: '',
        currentNasabahRekening: '-',
        currentNasabahSaldo: 0,
        depositType: 'drop_off',
        pendingDepositId: '',

        rows: [
            { itemId: rawList[0]?.id || 1, weight: 1, unit: rawList[0]?.unit || 'kg', price: parseFloat(rawList[0]?.buy_price_per_unit || 4500), points: 15, co2: 2.1 }
        ],

        updateNasabahInfo() {
            const selectEl = document.querySelector('select[name="user_id"]');
            const selectedOpt = selectEl.options[selectEl.selectedIndex];
            if (selectedOpt && selectedOpt.value) {
                this.currentNasabahRekening = selectedOpt.getAttribute('data-rekening') || '-';
                this.currentNasabahSaldo = parseFloat(selectedOpt.getAttribute('data-saldo')) || 0;
            } else {
                this.currentNasabahRekening = '-';
                this.currentNasabahSaldo = 0;
            }
        },

        loadPending(depositId, userId, code, type) {
            this.pendingDepositId = depositId;
            this.selectedUserId = userId;
            this.depositType = type;
            this.updateNasabahInfo();
            alert('Memuat permohonan setor ' + code + '. Silakan timbang sampah nasabah.');
        },

        addRow() {
            const first = this.wasteList[0] || { id: 1, unit: 'kg', price: 4500, points: 15, co2: 2.1 };
            this.rows.push({
                itemId: first.id,
                weight: 1,
                unit: first.unit,
                price: first.price,
                points: first.points,
                co2: first.co2
            });
        },

        removeRow(idx) {
            if (this.rows.length > 1) {
                this.rows.splice(idx, 1);
            }
        },

        updateRowItem(idx) {
            const selected = this.wasteList.find(i => i.id == this.rows[idx].itemId);
            if (selected) {
                this.rows[idx].price = selected.price;
                this.rows[idx].unit = selected.unit;
            }
        },

        get totalAmount() {
            return this.rows.reduce((sum, r) => sum + ((r.weight || 0) * (r.price || 0)), 0);
        },

        get totalWeight() {
            return this.rows.reduce((sum, r) => sum + (parseFloat(r.weight) || 0), 0);
        },

        formatRupiah(num) {
            return new Intl.NumberFormat('id-ID').format(Math.round(num || 0));
        }
    };
}
</script>
@endpush
