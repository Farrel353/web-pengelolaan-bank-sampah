@extends('layouts.app')

@section('title', 'Masuk ke Akun - EcoBank')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-6">
        <!-- Brand Header -->
        <div class="text-center">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 flex items-center justify-center text-white shadow-xl shadow-emerald-500/20 mx-auto text-2xl mb-3">
                <i class="fa-solid fa-recycle"></i>
            </div>
            <h2 class="text-2xl font-extrabold text-slate-900">Selamat Datang di EcoBank</h2>
            <p class="text-xs text-slate-500 mt-1">Masuk untuk mengakses buku tabungan atau panel admin</p>
        </div>

        <!-- Quick Demo Login Cards (Fitur Akses Cepat untuk Pengujian) -->
        <div class="p-4 rounded-2xl bg-emerald-50/80 border border-emerald-200/80 space-y-2.5">
            <div class="flex items-center gap-1.5 text-xs font-bold text-emerald-800 uppercase tracking-wider">
                <i class="fa-solid fa-wand-magic-sparkles text-emerald-600"></i> Akun Uji Coba Cepat (Demo):
            </div>
            <div class="grid grid-cols-3 gap-2 text-xs">
                <a href="{{ route('quick.switch', 'admin') }}" class="p-2 rounded-xl bg-white border border-emerald-300 text-center hover:bg-emerald-600 hover:text-white transition font-semibold text-slate-700 shadow-sm block">
                    <i class="fa-solid fa-shield-halved block text-sm mb-1 text-purple-600"></i> Admin
                </a>
                <a href="{{ route('quick.switch', 'petugas') }}" class="p-2 rounded-xl bg-white border border-emerald-300 text-center hover:bg-emerald-600 hover:text-white transition font-semibold text-slate-700 shadow-sm block">
                    <i class="fa-solid fa-scale-balanced block text-sm mb-1 text-amber-600"></i> Petugas
                </a>
                <a href="{{ route('quick.switch', 'nasabah') }}" class="p-2 rounded-xl bg-white border border-emerald-300 text-center hover:bg-emerald-600 hover:text-white transition font-semibold text-slate-700 shadow-sm block">
                    <i class="fa-solid fa-user block text-sm mb-1 text-emerald-600"></i> Nasabah
                </a>
            </div>
        </div>

        <!-- Standard Login Card -->
        <div class="p-8 rounded-3xl bg-white border border-slate-200 shadow-xl shadow-slate-200/40 space-y-6">
            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Email</label>
                    <div class="relative">
                        <input type="email" name="email" value="{{ old('email', 'admin@ecobank.id') }}" required class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                        <i class="fa-solid fa-envelope absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Kata Sandi</label>
                    </div>
                    <div class="relative">
                        <input type="password" name="password" value="password" required class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                        <i class="fa-solid fa-lock absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded text-emerald-600 focus:ring-emerald-500">
                        <span class="text-slate-600">Ingat saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3 px-4 rounded-xl font-bold text-sm text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 shadow-lg shadow-emerald-600/25 transition active:scale-98">
                    Masuk Sekarang
                </button>
            </form>

            <div class="pt-4 border-t border-slate-100 text-center text-xs text-slate-500">
                Belum memiliki buku rekening tabungan?
                <a href="{{ route('register') }}" class="font-bold text-emerald-600 hover:text-emerald-700 ml-1">
                    Daftar Nasabah Baru
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
