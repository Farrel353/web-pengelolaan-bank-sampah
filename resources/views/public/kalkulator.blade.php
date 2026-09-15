@extends('layouts.app')

@section('title', 'Kalkulator Simulasi Sampah - EcoBank')

@section('content')
<div class="bg-gradient-to-b from-emerald-50 to-slate-50 py-10 border-b border-slate-200/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <span class="text-xs font-bold uppercase tracking-widest text-emerald-600">Simulasi Tabungan Mandiri</span>
            <h1 class="text-3xl font-extrabold text-slate-900 mt-1">Kalkulator Tabungan Sampah</h1>
            <p class="text-slate-600 text-sm mt-2">
                Hitung estimasi uang tunai rupiah, perolehan eco-points, dan reduksi emisi karbon sebelum Anda menyetorkan sampah daur ulang.
            </p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="wasteCalculator()">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left Side: Interactive Input Lines -->
        <div class="lg:col-span-7 space-y-6">
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <h2 class="text-lg font-bold text-slate-800">Daftar Sampah yang Ingin Disetor</h2>
                    <button @click="addLine()" type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 transition">
                        <i class="fa-solid fa-plus"></i> Tambah Jenis Sampah
                    </button>
                </div>

                <!-- Repeatable Lines -->
                <div class="space-y-4">
                    <template x-for="(line, index) in lines" :key="index">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 relative space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider" x-text="'Item #' + (index + 1)"></span>
                                <button x-show="lines.length > 1" @click="removeLine(index)" type="button" class="text-xs text-rose-500 hover:text-rose-700 font-semibold flex items-center gap-1">
                                    <i class="fa-solid fa-trash-can"></i> Hapus
                                </button>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                                <!-- Waste Item Select -->
                                <div class="sm:col-span-7">
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Pilih Jenis Sampah</label>
                                    <select x-model="line.itemId" @change="updateItem(index)" class="w-full text-xs sm:text-sm rounded-xl border border-slate-200 bg-white px-3 py-2.5 focus:ring-2 focus:ring-emerald-500">
                                        <template x-for="item in availableItems" :key="item.id">
                                            <option :value="item.id" x-text="item.name + ' (Rp ' + formatRupiah(item.price) + '/' + item.unit + ')'"></option>
                                        </template>
                                    </select>
                                </div>

                                <!-- Weight Input -->
                                <div class="sm:col-span-5">
                                    <label class="block text-xs font-medium text-slate-600 mb-1">
                                        Berat / Jumlah (<span x-text="line.unit"></span>)
                                    </label>
                                    <div class="relative">
                                        <input type="number" step="0.1" min="0.1" x-model.number="line.weight" class="w-full text-xs sm:text-sm rounded-xl border border-slate-200 bg-white px-3 py-2.5 pr-12 focus:ring-2 focus:ring-emerald-500 font-bold text-slate-800">
                                        <span class="absolute right-3 top-2.5 text-xs text-slate-400 font-medium" x-text="line.unit"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Line Subtotal Feedback -->
                            <div class="flex items-center justify-between pt-2 border-t border-slate-200/60 text-xs">
                                <span class="text-slate-500">
                                    Subtotal: <strong class="text-emerald-700" x-text="'Rp ' + formatRupiah(line.weight * line.price)"></strong>
                                </span>
                                <span class="text-amber-600 font-semibold" x-text="'+' + Math.round(line.weight * line.points) + ' Poin'"></span>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="pt-2">
                    <button @click="addLine()" type="button" class="w-full py-3 rounded-2xl border-2 border-dashed border-emerald-300 text-emerald-700 font-semibold text-xs hover:bg-emerald-50 transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-plus-circle"></i> Tambah Komoditas Sampah Lainnya
                    </button>
                </div>
            </div>
        </div>

        <!-- Right Side: Simulation Summary Card -->
        <div class="lg:col-span-5 sticky top-24">
            <div class="p-6 sm:p-8 rounded-3xl bg-gradient-to-br from-emerald-900 via-teal-900 to-slate-900 text-white shadow-2xl space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-white/10">
                    <div>
                        <span class="text-[11px] uppercase tracking-wider text-emerald-400 font-bold">Hasil Perhitungan</span>
                        <h3 class="text-xl font-bold">Estimasi Pendapatan</h3>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-coins"></i>
                    </div>
                </div>

                <div class="space-y-4">
                    <!-- Rupiah Box -->
                    <div class="p-5 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20">
                        <span class="text-xs text-emerald-300 block">Total Tabungan Rupiah</span>
                        <p class="text-3xl font-extrabold text-white mt-1">
                            Rp <span x-text="formatRupiah(totalAmount)"></span>
                        </p>
                        <p class="text-[11px] text-slate-300 mt-1">Dapat langsung ditarik ke rekening bank atau e-wallet.</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Points Box -->
                        <div class="p-4 rounded-2xl bg-white/5 border border-white/10">
                            <span class="text-xs text-amber-300 block font-medium">Eco-Points</span>
                            <p class="text-2xl font-bold text-amber-400 mt-1" x-text="'+' + totalPoints"></p>
                            <span class="text-[10px] text-slate-400">Bisa ditukar sembako</span>
                        </div>

                        <!-- Carbon Box -->
                        <div class="p-4 rounded-2xl bg-white/5 border border-white/10">
                            <span class="text-xs text-emerald-300 block font-medium">Reduksi Emisi</span>
                            <p class="text-2xl font-bold text-emerald-400 mt-1">
                                <span x-text="totalCo2.toFixed(1)"></span> <span class="text-xs text-emerald-300">kg</span>
                            </p>
                            <span class="text-[10px] text-slate-400">Setara jejak karbon</span>
                        </div>
                    </div>
                </div>

                <!-- Total Weight Metric -->
                <div class="flex items-center justify-between text-xs py-3 px-4 rounded-xl bg-white/5 border border-white/10 text-slate-300">
                    <span>Estimasi Total Berat:</span>
                    <strong class="text-white" x-text="totalWeight.toFixed(1) + ' kg'"></strong>
                </div>

                <!-- CTA Button -->
                <div class="pt-2 space-y-3">
                    <a href="{{ route('register') }}" class="w-full py-3.5 px-4 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-white font-bold text-sm text-center block shadow-lg shadow-emerald-500/30 transition active:scale-95">
                        <i class="fa-solid fa-arrow-right-to-bracket mr-1"></i> Buka Akun & Setorkan Sampah
                    </a>
                    <a href="{{ route('katalog') }}" class="w-full py-2.5 px-4 rounded-2xl bg-transparent hover:bg-white/10 text-slate-300 font-medium text-xs text-center block transition">
                        Periksa Rincian Katalog Harga
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
function wasteCalculator() {
    const rawItems = @json($allItems);

    return {
        availableItems: rawItems.map(i => ({
            id: i.id,
            name: i.name,
            unit: i.unit,
            price: parseFloat(i.buy_price_per_unit),
            points: parseInt(i.points_per_unit),
            co2: parseFloat(i.co2_factor_per_unit)
        })),

        lines: [
            { itemId: rawItems[0]?.id || 1, weight: 5, unit: rawItems[0]?.unit || 'kg', price: parseFloat(rawItems[0]?.buy_price_per_unit || 4500), points: parseInt(rawItems[0]?.points_per_unit || 15), co2: parseFloat(rawItems[0]?.co2_factor_per_unit || 2.1) },
            { itemId: rawItems[2]?.id || 3, weight: 10, unit: rawItems[2]?.unit || 'kg', price: parseFloat(rawItems[2]?.buy_price_per_unit || 2200), points: parseInt(rawItems[2]?.points_per_unit || 8), co2: parseFloat(rawItems[2]?.co2_factor_per_unit || 1.2) }
        ],

        addLine() {
            const first = this.availableItems[0] || { id: 1, unit: 'kg', price: 4500, points: 15, co2: 2.1 };
            this.lines.push({
                itemId: first.id,
                weight: 1,
                unit: first.unit,
                price: first.price,
                points: first.points,
                co2: first.co2
            });
        },

        removeLine(index) {
            if (this.lines.length > 1) {
                this.lines.splice(index, 1);
            }
        },

        updateItem(index) {
            const selected = this.availableItems.find(i => i.id == this.lines[index].itemId);
            if (selected) {
                this.lines[index].price = selected.price;
                this.lines[index].points = selected.points;
                this.lines[index].unit = selected.unit;
                this.lines[index].co2 = selected.co2;
            }
        },

        get totalAmount() {
            return this.lines.reduce((sum, line) => sum + ((line.weight || 0) * (line.price || 0)), 0);
        },

        get totalPoints() {
            return this.lines.reduce((sum, line) => sum + Math.round((line.weight || 0) * (line.points || 0)), 0);
        },

        get totalWeight() {
            return this.lines.reduce((sum, line) => sum + (parseFloat(line.weight) || 0), 0);
        },

        get totalCo2() {
            return this.lines.reduce((sum, line) => sum + ((line.weight || 0) * (line.co2 || 0)), 0);
        },

        formatRupiah(num) {
            return new Intl.NumberFormat('id-ID').format(Math.round(num || 0));
        }
    };
}
</script>
@endpush
