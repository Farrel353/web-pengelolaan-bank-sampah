@extends('layouts.app')

@section('title', 'Fitur 1: Mengelola Data Nasabah - Pengurus')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8" x-data="{ showAddModal: false, editModalData: null }">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 mb-2">
                <i class="fa-solid fa-users"></i> Fitur 1 Pengurus: Mengelola Data Nasabah
            </span>
            <h1 class="text-2xl font-extrabold text-slate-900">Pengelolaan Database Nasabah</h1>
            <p class="text-xs text-slate-500 mt-1">
                Pengurus dapat menambah, mengubah, menghapus, dan melihat data nasabah serta melihat riwayat transaksi nasabah.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <button @click="showAddModal = true" type="button" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold text-xs shadow-md shadow-emerald-600/20 transition flex items-center gap-1.5">
                <i class="fa-solid fa-user-plus"></i> + Tambah Nasabah Baru
            </button>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between gap-4">
        <form action="{{ route('admin.nasabah.index') }}" method="GET" class="relative flex-1">
            <input type="text" name="cari" value="{{ $search }}" placeholder="Cari nama, no rekening (BS-2026-...), email, atau nomor HP..." class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-xs"></i>
        </form>
        @if($search)
            <a href="{{ route('admin.nasabah.index') }}" class="px-3 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">
                Reset
            </a>
        @endif
    </div>

    <!-- Tabel Data Nasabah (CRUD Lengkap) -->
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        @if($nasabahs->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[11px] border-b border-slate-100">
                        <tr>
                            <th class="py-4 px-6">Nama Nasabah</th>
                            <th class="py-4 px-6">No. Rekening</th>
                            <th class="py-4 px-6">Kontak / HP</th>
                            <th class="py-4 px-6">Alamat Rumah</th>
                            <th class="py-4 px-6">Saldo Terkini (Rp)</th>
                            <th class="py-4 px-6 text-right">Aksi Kelola</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($nasabahs as $n)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-800 font-bold flex items-center justify-center text-xs">
                                            {{ strtoupper(substr($n->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.nasabah.show', $n->id) }}" class="font-bold text-slate-900 text-xs sm:text-sm hover:text-emerald-700 block">
                                                {{ $n->name }}
                                            </a>
                                            <span class="text-[10px] text-slate-400">{{ $n->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6 font-mono font-bold text-slate-700">
                                    {{ $n->nasabahProfile?->no_rekening ?? '-' }}
                                </td>
                                <td class="py-4 px-6 text-slate-700 font-medium">
                                    {{ $n->phone }}
                                </td>
                                <td class="py-4 px-6 text-slate-500 text-xs max-w-xs truncate">
                                    {{ $n->nasabahProfile?->address ?? '-' }} {{ $n->nasabahProfile?->rt_rw ? '(RT ' . $n->nasabahProfile->rt_rw . ')' : '' }}
                                </td>
                                <td class="py-4 px-6 font-extrabold text-emerald-600 text-sm">
                                    Rp {{ number_format($n->nasabahProfile?->balance ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <!-- Lihat Data & Riwayat Transaksi -->
                                        <a href="{{ route('admin.nasabah.show', $n->id) }}" class="px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 text-slate-700 font-bold text-xs transition" title="Lihat Riwayat Transaksi">
                                            <i class="fa-solid fa-eye mr-1"></i> Riwayat
                                        </a>

                                        <!-- Mengubah Data Nasabah (Edit) -->
                                        <button type="button" @click="editModalData = { id: {{ $n->id }}, name: '{{ addslashes($n->name) }}', email: '{{ addslashes($n->email) }}', phone: '{{ addslashes($n->phone) }}', address: '{{ addslashes($n->nasabahProfile?->address ?? '') }}', rt_rw: '{{ addslashes($n->nasabahProfile?->rt_rw ?? '') }}' }" class="px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-blue-50 hover:text-blue-700 text-slate-700 font-bold text-xs transition" title="Ubah Data Nasabah">
                                            <i class="fa-solid fa-pen"></i> Ubah
                                        </button>

                                        <!-- Menghapus Data Nasabah (Delete) -->
                                        <form action="{{ route('admin.nasabah.destroy', $n->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus nasabah {{ addslashes($n->name) }} beserta riwayatnya?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-rose-50 hover:text-rose-700 text-slate-700 font-bold text-xs transition" title="Hapus Nasabah">
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

            <div class="p-4 border-t border-slate-100">
                {{ $nasabahs->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <i class="fa-solid fa-users text-4xl text-slate-300 mb-3 block"></i>
                <h3 class="text-base font-bold text-slate-800">Tidak Ada Data Nasabah</h3>
                <p class="text-xs text-slate-400 mt-1">Daftarkan nasabah baru atau ganti kata kunci pencarian.</p>
            </div>
        @endif
    </div>

    <!-- MODAL 1: TAMBAH DATA NASABAH -->
    <div x-show="showAddModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" x-cloak>
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 space-y-4 shadow-2xl" @click.away="showAddModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="font-extrabold text-base text-slate-900">Tambah Data Nasabah Baru</h3>
                <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-600 text-lg">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('admin.nasabah.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap</label>
                    <input type="text" name="name" required placeholder="Sesuai KTP" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Alamat Email</label>
                        <input type="email" name="email" required placeholder="nama@email.com" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">No. WhatsApp / HP</label>
                        <input type="tel" name="phone" required placeholder="08123xxxx" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Alamat Rumah</label>
                        <input type="text" name="address" required placeholder="Jl. Mawar No. 12" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">RT / RW</label>
                        <input type="text" name="rt_rw" placeholder="03/05" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Saldo Awal (Rp)</label>
                    <input type="number" name="initial_balance" value="0" min="0" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50">
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" @click="showAddModal = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md">Simpan Nasabah</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: MENGUBAH DATA NASABAH (EDIT) -->
    <div x-show="editModalData" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" x-cloak>
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 space-y-4 shadow-2xl" @click.away="editModalData = null">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="font-extrabold text-base text-slate-900">Ubah Data Nasabah</h3>
                <button @click="editModalData = null" class="text-slate-400 hover:text-slate-600 text-lg">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form :action="'/admin/nasabah/' + editModalData?.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap</label>
                    <input type="text" name="name" :value="editModalData?.name" required class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email</label>
                        <input type="email" name="email" :value="editModalData?.email" required class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">No. WhatsApp / HP</label>
                        <input type="tel" name="phone" :value="editModalData?.phone" required class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Alamat Rumah</label>
                        <input type="text" name="address" :value="editModalData?.address" required class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">RT / RW</label>
                        <input type="text" name="rt_rw" :value="editModalData?.rt_rw" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50">
                    </div>
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" @click="editModalData = null" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
