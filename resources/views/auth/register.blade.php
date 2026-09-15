@extends('layouts.app')

@section('title', 'Daftar Nasabah Baru - EcoBank')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-lg w-full space-y-6">
        <div class="text-center">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 flex items-center justify-center text-white shadow-xl shadow-emerald-500/20 mx-auto text-2xl mb-3">
                <i class="fa-solid fa-seedling"></i>
            </div>
            <h2 class="text-2xl font-extrabold text-slate-900">Buka Rekening Bank Sampah</h2>
            <p class="text-xs text-slate-500 mt-1">Gratis, instan, dan dapatkan bonus 100 Eco-Points pertama Anda!</p>
        </div>

        <div class="p-8 rounded-3xl bg-white border border-slate-200 shadow-xl shadow-slate-200/40 space-y-6">
            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Lengkap (Sesuai KTP)</label>
                    <div class="relative">
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                        <i class="fa-solid fa-user absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Email</label>
                        <div class="relative">
                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                            <i class="fa-solid fa-envelope absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor WhatsApp / HP</label>
                        <div class="relative">
                            <input type="tel" name="phone" value="{{ old('phone') }}" required placeholder="081234567890" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                            <i class="fa-solid fa-phone absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Tempat Tinggal (Untuk Penjemputan)</label>
                    <div class="relative">
                        <textarea name="address" rows="2" required placeholder="Jl. Mawar No. 12, RT 02/RW 04, Kelurahan..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm"></textarea>
                        <i class="fa-solid fa-location-dot absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kata Sandi</label>
                        <div class="relative">
                            <input type="password" name="password" required class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                            <i class="fa-solid fa-lock absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Ulangi Kata Sandi</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" required class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                            <i class="fa-solid fa-shield-check absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 px-4 rounded-xl font-bold text-sm text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 shadow-lg shadow-emerald-600/25 transition active:scale-98">
                    Buat Akun & Dapatkan No. Rekening
                </button>
            </form>

            <div class="pt-4 border-t border-slate-100 text-center text-xs text-slate-500">
                Sudah memiliki akun nasabah?
                <a href="{{ route('login') }}" class="font-bold text-emerald-600 hover:text-emerald-700 ml-1">
                    Masuk Sekarang
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
