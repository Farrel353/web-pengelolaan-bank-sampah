@extends('layouts.app')

@section('title', 'Fitur 2: Mengelola Jenis dan Harga Sampah - Pengurus')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8" x-data="{ showItemModal: false, showCategoryModal: false, editItem: null }">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 mb-2">
                <i class="fa-solid fa-tags"></i> Fitur 2 Pengurus: Mengelola Jenis dan Harga Sampah
            </span>
            <h1 class="text-2xl font-extrabold text-slate-900">Katalog Jenis & Penetapan Harga Sampah</h1>
            <p class="text-xs text-slate-500 mt-1">
                Pengurus dapat menambah dan mengubah jenis sampah serta menentukan harga setiap jenis sampah berdasarkan harga yang berlaku.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <button @click="showCategoryModal = true" type="button" class="px-3.5 py-2 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50 font-bold text-xs transition flex items-center gap-1.5">
                <i class="fa-solid fa-folder-plus"></i> + Kategori
            </button>
            <button @click="showItemModal = true" type="button" class="px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold text-xs shadow-md shadow-emerald-600/20 transition flex items-center gap-1.5">
                <i class="fa-solid fa-plus-circle"></i> + Tambah Jenis Sampah
            </button>
        </div>
    </div>

    <!-- Tabel Daftar Jenis & Harga Sampah -->
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[11px] border-b border-slate-100">
                    <tr>
                        <th class="py-4 px-6">Jenis Sampah</th>
                        <th class="py-4 px-6">Kategori</th>
                        <th class="py-4 px-6">Satuan</th>
                        <th class="py-4 px-6">Harga per Kilogram (Rp)</th>
                        <th class="py-4 px-6">Status Berlaku</th>
                        <th class="py-4 px-6 text-right">Aksi Kelola</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($items as $item)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-4 px-6">
                                <strong class="text-slate-900 block text-xs sm:text-sm">{{ $item->name }}</strong>
                                <span class="text-[11px] text-slate-400">{{ $item->description ?? 'Tanpa deskripsi' }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                    {{ $item->category->name }}
                                </span>
                            </td>
                            <td class="py-4 px-6 font-semibold text-slate-700 uppercase">
                                {{ $item->unit }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="text-base font-extrabold text-emerald-600">
                                    Rp {{ number_format($item->buy_price_per_unit, 0, ',', '.') }}
                                </span>
                                <span class="text-xs text-slate-400 font-normal">/ {{ $item->unit }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <form action="{{ route('admin.sampah.toggle', $item->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase transition {{ $item->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-slate-200 text-slate-600 hover:bg-slate-300' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $item->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                        {{ $item->is_active ? 'Berlaku' : 'Nonaktif' }}
                                    </button>
                                </form>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- Tombol Ubah / Tentukan Harga Baru -->
                                    <button @click="editItem = {{ json_encode($item) }}" type="button" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 text-slate-700 font-bold text-xs transition">
                                        <i class="fa-solid fa-pen mr-1"></i> Ubah Harga
                                    </button>

                                    <!-- Tombol Hapus Jenis Sampah -->
                                    <form action="{{ route('admin.sampah.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus jenis sampah {{ addslashes($item->name) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-rose-50 hover:text-rose-700 text-slate-700 font-bold text-xs transition" title="Hapus">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Jenis Sampah Baru -->
    <div x-show="showItemModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" x-cloak>
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-4 shadow-2xl" @click.away="showItemModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="font-extrabold text-base text-slate-900">Tambah Jenis Sampah Baru</h3>
                <button @click="showItemModal = false" class="text-slate-400 hover:text-slate-600 text-lg">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('admin.sampah.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kategori Sampah</label>
                    <select name="waste_category_id" required class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Jenis Sampah</label>
                    <input type="text" name="name" required placeholder="Contoh: Kardus Box Tebal" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Satuan</label>
                        <select name="unit" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50">
                            <option value="kg">kg (Kilogram)</option>
                            <option value="liter">liter</option>
                            <option value="pcs">pcs</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Harga Beli per Kilogram (Rp)</label>
                        <input type="number" name="buy_price_per_unit" required min="0" placeholder="2500" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-bold text-emerald-700">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Keterangan / Persyaratan Kondisi</label>
                    <textarea name="description" rows="2" placeholder="Harus kering, tidak basah atau berminyak..." class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50"></textarea>
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" @click="showItemModal = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md">Simpan Jenis Sampah</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Mengubah Jenis & Harga Sampah -->
    <div x-show="editItem" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" x-cloak>
        <div class="bg-white rounded-3xl max-w-md w-full p-6 space-y-4 shadow-2xl" @click.away="editItem = null">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="font-extrabold text-sm text-slate-900">Ubah Data & Tentukan Harga</h3>
                    <p class="text-[11px] text-slate-400" x-text="editItem?.name"></p>
                </div>
                <button @click="editItem = null" class="text-slate-400 hover:text-slate-600 text-lg">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form :action="'/admin/sampah/' + editItem?.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Jenis Sampah</label>
                    <input type="text" name="name" :value="editItem?.name" required class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Harga per Kilogram Baru (Rp)</label>
                    <input type="number" name="buy_price_per_unit" :value="editItem?.buy_price_per_unit" required min="0" class="w-full px-3 py-2 text-sm font-bold text-emerald-700 rounded-xl border border-slate-200 bg-slate-50">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Keterangan / Kondisi</label>
                    <input type="text" name="description" :value="editItem?.description" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50">
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" @click="editItem = null" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md">Perbarui Harga</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Tambah Kategori Baru -->
    <div x-show="showCategoryModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" x-cloak>
        <div class="bg-white rounded-3xl max-w-md w-full p-6 space-y-4 shadow-2xl" @click.away="showCategoryModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="font-extrabold text-sm text-slate-900">Tambah Kategori Baru</h3>
                <button @click="showCategoryModal = false" class="text-slate-400 hover:text-slate-600 text-lg">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('admin.kategori.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Kategori</label>
                    <input type="text" name="name" required placeholder="Contoh: Logam Mulia / Kuningan" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Deskripsi Kategori</label>
                    <textarea name="description" rows="2" placeholder="Penjelasan tentang jenis limbah kategori ini..." class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50"></textarea>
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" @click="showCategoryModal = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-md">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
