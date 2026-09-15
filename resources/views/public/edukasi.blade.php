@extends('layouts.app')

@section('title', 'Panduan 3R & Pemilahan Sampah - EcoBank')

@section('content')
<div class="bg-gradient-to-b from-emerald-50 to-slate-50 py-10 border-b border-slate-200/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <span class="text-xs font-bold uppercase tracking-widest text-emerald-600">Edukasi Lingkungan</span>
            <h1 class="text-3xl font-extrabold text-slate-900 mt-1">Panduan Pemilahan Sampah & 3R</h1>
            <p class="text-slate-600 text-sm mt-2">
                Pelajari cara memilah sampah rumah tangga agar memiliki nilai jual daur ulang yang optimal dan berdampak positif bagi ekosistem.
            </p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">
    <!-- 4 Kategori Pemilahan Utama -->
    <div>
        <h2 class="text-2xl font-extrabold text-slate-900 mb-6">Kenali 4 Kategori Pemilahan Sampah</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Plastik & Daur Ulang -->
            <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl font-bold">
                    <i class="fa-solid fa-bottle-water"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Anorganik Daur Ulang</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Botol plastik PET, kaleng aluminium, kardus, kertas dokumen, dan botol kaca. <strong>Ini jenis sampah yang disetorkan ke EcoBank!</strong>
                </p>
                <div class="pt-2 text-xs font-semibold text-emerald-600">
                    <i class="fa-solid fa-circle-check mr-1"></i> Bernilai Rupiah Tinggi
                </div>
            </div>

            <!-- Organik -->
            <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl font-bold">
                    <i class="fa-solid fa-apple-whole"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Organik (Basah)</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Sisa sayuran, kulit buah, sisa nasi, dedaunan. Sangat baik diolah menjadi kompos atau pupuk cair (Eco-Enzyme) secara mandiri di rumah.
                </p>
                <div class="pt-2 text-xs font-semibold text-emerald-600">
                    <i class="fa-solid fa-seedling mr-1"></i> Bahan Baku Kompos
                </div>
            </div>

            <!-- Minyak & B3 -->
            <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-teal-100 text-teal-600 flex items-center justify-center text-xl font-bold">
                    <i class="fa-solid fa-oil-can"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Minyak Jelantah & E-Waste</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Minyak jelantah sisa dapur yang disaring dan barang elektronik rusak. Jangan dibuang ke wastafel atau selokan!
                </p>
                <div class="pt-2 text-xs font-semibold text-teal-600">
                    <i class="fa-solid fa-gas-pump mr-1"></i> Bahan Baku Biofuel
                </div>
            </div>

            <!-- Residu -->
            <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-600 flex items-center justify-center text-xl font-bold">
                    <i class="fa-solid fa-trash"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Sampah Residu</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Popok bekas, tisu kotor, puntung rokok, plastik sachet berlapis foil. Jenis ini diteruskan ke Tempat Pemrosesan Akhir (TPA).
                </p>
                <div class="pt-2 text-xs font-semibold text-slate-500">
                    <i class="fa-solid fa-ban mr-1"></i> Minimalkan Penggunaan
                </div>
            </div>
        </div>
    </div>

    <!-- Tips Menyetor Sampah Berharga Tinggi -->
    <div class="p-8 rounded-3xl bg-gradient-to-br from-slate-900 to-emerald-950 text-white shadow-xl space-y-6">
        <div class="max-w-xl">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-400">Tips Nasabah Cerdas</span>
            <h2 class="text-2xl font-extrabold mt-1">Cara Menyetor Agar Nilai Beli Maksimal</h2>
            <p class="text-slate-300 text-xs mt-2">
                Petugas bank sampah akan menguji kebersihan dan kadar air saat menimbang. Ikuti panduan ini:
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-2">
            <div class="p-5 rounded-2xl bg-white/10 border border-white/15 backdrop-blur-md space-y-2">
                <div class="text-emerald-400 font-bold text-base flex items-center gap-2">
                    <i class="fa-solid fa-droplet-slash"></i> 1. Bersih & Kering
                </div>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Bilas botol plastik dari sisa sirup atau susu, lalu keringkan. Sampah yang basah dapat ditolak atau dikurangi timbangannya.
                </p>
            </div>

            <div class="p-5 rounded-2xl bg-white/10 border border-white/15 backdrop-blur-md space-y-2">
                <div class="text-teal-400 font-bold text-base flex items-center gap-2">
                    <i class="fa-solid fa-compress"></i> 2. Pipihkan / Remas
                </div>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Injak dan pipihkan botol plastik dan kardus box. Ini menghemat volume tempat penyimpanan Anda dan memudahkan pengangkutan.
                </p>
            </div>

            <div class="p-5 rounded-2xl bg-white/10 border border-white/15 backdrop-blur-md space-y-2">
                <div class="text-amber-400 font-bold text-base flex items-center gap-2">
                    <i class="fa-solid fa-layer-group"></i> 3. Kelompokkan Jenisnya
                </div>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Pisahkan kardus dari kertas putih HVS, serta botol bening dari botol warna. Pemilahan yang rapi mempercepat proses penimbangan di kasir.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
