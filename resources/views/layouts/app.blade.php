<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Bank Sampah')</title>
    
    <!-- Google Font: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Alpine.js & Font Awesome CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Tailwind CSS (Compiled Assets via Vite & Tailwind CDN) -->
    <link rel="stylesheet" href="/build/assets/style-38CgNKDJ.css">
    <script defer src="/build/assets/app-KsQXAh3k.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        eco: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                            950: '#022c22',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="h-full flex flex-col bg-slate-50 text-slate-800 antialiased selection:bg-emerald-500 selection:text-white">

    <!-- Top Role Simulator Switcher Bar -->
    <div class="bg-slate-900 text-xs text-slate-300 py-1.5 px-4 border-b border-slate-800 flex flex-wrap items-center justify-between gap-2">
        <div class="flex items-center gap-2">
            <span class="flex h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span class="font-medium text-slate-200">Aktor Sistem:</span>
            <span class="text-slate-400">
                @auth
                    Sedang masuk: <strong class="text-emerald-400">{{ Auth::user()->name }}</strong> ({{ Auth::user()->isNasabah() ? 'Nasabah' : 'Pengurus/Admin' }})
                @else
                    Tamu (Belum Login)
                @endauth
            </span>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-slate-400">Simulasi Cepat:</span>
            <a href="{{ route('quick.switch', 'nasabah') }}" class="px-2 py-0.5 rounded bg-slate-800 hover:bg-emerald-600 hover:text-white transition text-slate-300">
                <i class="fa-solid fa-user mr-1 text-emerald-400"></i> Aktor: Nasabah (Budi)
            </a>
            <a href="{{ route('quick.switch', 'admin') }}" class="px-2 py-0.5 rounded bg-slate-800 hover:bg-amber-600 hover:text-white transition text-slate-300">
                <i class="fa-solid fa-gear mr-1 text-amber-400"></i> Aktor: Pengurus/Admin
            </a>
            <a href="{{ route('home') }}" class="px-2 py-0.5 rounded bg-slate-800 hover:bg-slate-700 text-slate-300">Publik</a>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200 shadow-sm" x-data="{ mobileMenu: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-18 items-center">
                <!-- Brand Logo -->
                <div class="flex items-center gap-6">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                        <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 flex items-center justify-center text-white shadow-lg shadow-emerald-500/25 group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-recycle text-xl"></i>
                        </div>
                        <div>
                            <span class="text-xl font-extrabold tracking-tight bg-gradient-to-r from-emerald-800 to-teal-700 bg-clip-text text-transparent">Bank Sampah</span>
                            <span class="block text-[10px] font-semibold tracking-wider text-emerald-600 uppercase -mt-1">Sistem Pengelolaan</span>
                        </div>
                    </a>

                    <!-- Navigation Links Berdasarkan Aktor Sesuai Diagram Konstruksi -->
                    <nav class="hidden lg:flex items-center gap-1">
                        @auth
                            @if(Auth::user()->isNasabah())
                                <!-- 4 Fitur Nasabah -->
                                <a href="{{ route('nasabah.dashboard') }}" class="px-3 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ request()->routeIs('nasabah.dashboard') ? 'text-emerald-700 bg-emerald-50' : 'text-slate-600 hover:text-emerald-600 hover:bg-slate-50' }}">
                                    <i class="fa-solid fa-wallet text-emerald-600"></i> 1. Melihat Saldo
                                </a>
                                <a href="{{ route('nasabah.setor.index') }}" class="px-3 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ request()->routeIs('nasabah.setor.*') ? 'text-emerald-700 bg-emerald-50' : 'text-slate-600 hover:text-emerald-600 hover:bg-slate-50' }}">
                                    <i class="fa-solid fa-clock-rotate-left text-emerald-600"></i> 2. Riwayat Setoran
                                </a>
                                <a href="{{ route('nasabah.tarik.index') }}" class="px-3 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ request()->routeIs('nasabah.tarik.*') ? 'text-emerald-700 bg-emerald-50' : 'text-slate-600 hover:text-emerald-600 hover:bg-slate-50' }}">
                                    <i class="fa-solid fa-money-bill-transfer text-emerald-600"></i> 3. Riwayat Penarikan
                                </a>
                                <a href="{{ route('nasabah.harga') }}" class="px-3 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ request()->routeIs('nasabah.harga') ? 'text-emerald-700 bg-emerald-50' : 'text-slate-600 hover:text-emerald-600 hover:bg-slate-50' }}">
                                    <i class="fa-solid fa-tags text-emerald-600"></i> 4. Melihat Harga Sampah
                                </a>
                            @else
                                <!-- 5 Fitur Pengurus/Admin -->
                                <a href="{{ route('admin.nasabah.index') }}" class="px-3 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ request()->routeIs('admin.nasabah.*') ? 'text-amber-800 bg-amber-50' : 'text-slate-600 hover:text-amber-700 hover:bg-slate-50' }}">
                                    <i class="fa-solid fa-users text-amber-600"></i> 1. Data Nasabah
                                </a>
                                <a href="{{ route('admin.sampah.index') }}" class="px-3 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ request()->routeIs('admin.sampah.*') ? 'text-amber-800 bg-amber-50' : 'text-slate-600 hover:text-amber-700 hover:bg-slate-50' }}">
                                    <i class="fa-solid fa-tags text-amber-600"></i> 2. Jenis & Harga Sampah
                                </a>
                                <a href="{{ route('admin.timbang.index') }}" class="px-3 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ request()->routeIs('admin.timbang.*') ? 'text-amber-800 bg-amber-50' : 'text-slate-600 hover:text-amber-700 hover:bg-slate-50' }}">
                                    <i class="fa-solid fa-scale-balanced text-amber-600"></i> 3. Catat Setoran
                                </a>
                                <a href="{{ route('admin.penarikan.index') }}" class="px-3 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ request()->routeIs('admin.penarikan.*') ? 'text-amber-800 bg-amber-50' : 'text-slate-600 hover:text-amber-700 hover:bg-slate-50' }}">
                                    <i class="fa-solid fa-hand-holding-dollar text-amber-600"></i> 4. Proses Penarikan
                                </a>
                                <a href="{{ route('admin.laporan.index') }}" class="px-3 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ request()->routeIs('admin.laporan.*') ? 'text-amber-800 bg-amber-50' : 'text-slate-600 hover:text-amber-700 hover:bg-slate-50' }}">
                                    <i class="fa-solid fa-chart-column text-amber-600"></i> 5. Laporan Transaksi
                                </a>
                            @endif
                        @else
                            <a href="{{ route('home') }}" class="px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('home') ? 'text-emerald-700 bg-emerald-50' : 'text-slate-600 hover:text-emerald-600 hover:bg-slate-50' }}">
                                Beranda
                            </a>
                            <a href="{{ route('katalog') }}" class="px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('katalog') ? 'text-emerald-700 bg-emerald-50' : 'text-slate-600 hover:text-emerald-600 hover:bg-slate-50' }}">
                                Harga Sampah
                            </a>
                            <a href="{{ route('kalkulator') }}" class="px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('kalkulator') ? 'text-emerald-700 bg-emerald-50' : 'text-slate-600 hover:text-emerald-600 hover:bg-slate-50' }}">
                                Kalkulator Sampah
                            </a>
                        @endauth
                    </nav>
                </div>

                <!-- Right Side Actions & User Profile -->
                <div class="hidden md:flex items-center gap-3">
                    @auth
                        <!-- User Profile Dropdown -->
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open" class="flex items-center gap-2.5 p-1.5 rounded-2xl hover:bg-slate-100 transition border border-slate-200">
                                <div class="w-8 h-8 rounded-xl {{ Auth::user()->isNasabah() ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }} font-bold flex items-center justify-center text-xs">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </div>
                                <div class="text-left">
                                    <span class="text-xs font-bold text-slate-800 block truncate max-w-[120px]">{{ Auth::user()->name }}</span>
                                    <span class="text-[10px] text-slate-400 block -mt-0.5">{{ Auth::user()->isNasabah() ? 'Nasabah' : 'Pengurus' }}</span>
                                </div>
                                <i class="fa-solid fa-chevron-down text-xs text-slate-400 mr-1"></i>
                            </button>

                            <div x-show="open" 
                                 x-transition
                                 class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50">
                                <div class="px-4 py-2 border-b border-slate-100">
                                    <p class="text-[10px] text-slate-400 uppercase">Aktor Aktif</p>
                                    <p class="text-xs font-bold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                                    <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ Auth::user()->isNasabah() ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-800' }}">
                                        {{ Auth::user()->isNasabah() ? 'Nasabah' : 'Pengurus / Admin' }}
                                    </span>
                                </div>

                                @if(Auth::user()->isNasabah())
                                    <a href="{{ route('nasabah.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                                        <i class="fa-solid fa-wallet text-slate-400 w-4"></i> Melihat Saldo
                                    </a>
                                    <a href="{{ route('nasabah.setor.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                                        <i class="fa-solid fa-clock-rotate-left text-slate-400 w-4"></i> Melihat Riwayat Setoran
                                    </a>
                                    <a href="{{ route('nasabah.tarik.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                                        <i class="fa-solid fa-money-bill-transfer text-slate-400 w-4"></i> Melihat Riwayat Penarikan
                                    </a>
                                    <a href="{{ route('nasabah.harga') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                                        <i class="fa-solid fa-tags text-slate-400 w-4"></i> Melihat Harga Sampah
                                    </a>
                                @else
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-slate-700 hover:bg-amber-50 hover:text-amber-800">
                                        <i class="fa-solid fa-house text-slate-400 w-4"></i> Dashboard Pengurus
                                    </a>
                                    <a href="{{ route('admin.nasabah.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-slate-700 hover:bg-amber-50 hover:text-amber-800">
                                        <i class="fa-solid fa-users text-slate-400 w-4"></i> Mengelola Data Nasabah
                                    </a>
                                    <a href="{{ route('admin.sampah.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-slate-700 hover:bg-amber-50 hover:text-amber-800">
                                        <i class="fa-solid fa-tags text-slate-400 w-4"></i> Mengelola Jenis & Harga
                                    </a>
                                    <a href="{{ route('admin.timbang.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-slate-700 hover:bg-amber-50 hover:text-amber-800">
                                        <i class="fa-solid fa-scale-balanced text-slate-400 w-4"></i> Mencatat Setoran Sampah
                                    </a>
                                    <a href="{{ route('admin.penarikan.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-slate-700 hover:bg-amber-50 hover:text-amber-800">
                                        <i class="fa-solid fa-hand-holding-dollar text-slate-400 w-4"></i> Memproses Penarikan
                                    </a>
                                    <a href="{{ route('admin.laporan.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-slate-700 hover:bg-amber-50 hover:text-amber-800">
                                        <i class="fa-solid fa-chart-column text-slate-400 w-4"></i> Melihat Laporan Transaksi
                                    </a>
                                @endif

                                <div class="border-t border-slate-100 my-1"></div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center gap-2.5 px-4 py-2 text-xs text-rose-600 hover:bg-rose-50">
                                        <i class="fa-solid fa-arrow-right-from-bracket w-4"></i> Keluar (Logout)
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-700 hover:text-emerald-600 transition">
                            Masuk (Login)
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 shadow-md shadow-emerald-600/20 transition">
                            Daftar Nasabah
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Toggle -->
                <div class="flex lg:hidden items-center gap-2">
                    <button @click="mobileMenu = !mobileMenu" class="p-2 rounded-xl text-slate-600 hover:bg-slate-100">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div x-show="mobileMenu" class="lg:hidden border-t border-slate-100 bg-white px-4 pt-2 pb-4 space-y-2" x-cloak>
            @auth
                @if(Auth::user()->isNasabah())
                    <p class="text-[10px] uppercase font-bold text-emerald-700 px-3 pt-2">Menu Nasabah (4 Fitur)</p>
                    <a href="{{ route('nasabah.dashboard') }}" class="block px-3 py-2 rounded-xl text-xs font-bold text-slate-700 hover:bg-emerald-50">1. Melihat Saldo</a>
                    <a href="{{ route('nasabah.setor.index') }}" class="block px-3 py-2 rounded-xl text-xs font-bold text-slate-700 hover:bg-emerald-50">2. Melihat Riwayat Setoran</a>
                    <a href="{{ route('nasabah.tarik.index') }}" class="block px-3 py-2 rounded-xl text-xs font-bold text-slate-700 hover:bg-emerald-50">3. Melihat Riwayat Penarikan</a>
                    <a href="{{ route('nasabah.harga') }}" class="block px-3 py-2 rounded-xl text-xs font-bold text-slate-700 hover:bg-emerald-50">4. Melihat Harga Sampah</a>
                @else
                    <p class="text-[10px] uppercase font-bold text-amber-700 px-3 pt-2">Menu Pengurus (5 Fitur)</p>
                    <a href="{{ route('admin.nasabah.index') }}" class="block px-3 py-2 rounded-xl text-xs font-bold text-slate-700 hover:bg-amber-50">1. Mengelola Data Nasabah</a>
                    <a href="{{ route('admin.sampah.index') }}" class="block px-3 py-2 rounded-xl text-xs font-bold text-slate-700 hover:bg-amber-50">2. Mengelola Jenis & Harga</a>
                    <a href="{{ route('admin.timbang.index') }}" class="block px-3 py-2 rounded-xl text-xs font-bold text-slate-700 hover:bg-amber-50">3. Mencatat Setoran Sampah</a>
                    <a href="{{ route('admin.penarikan.index') }}" class="block px-3 py-2 rounded-xl text-xs font-bold text-slate-700 hover:bg-amber-50">4. Memproses Penarikan Saldo</a>
                    <a href="{{ route('admin.laporan.index') }}" class="block px-3 py-2 rounded-xl text-xs font-bold text-slate-700 hover:bg-amber-50">5. Melihat Laporan Transaksi</a>
                @endif
                <form action="{{ route('logout') }}" method="POST" class="pt-2 border-t border-slate-100">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-xl text-xs font-bold text-rose-600">Keluar (Logout)</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block px-3 py-2 rounded-xl text-xs font-bold text-slate-700">Masuk</a>
                <a href="{{ route('register') }}" class="block px-3 py-2 rounded-xl text-xs font-bold text-emerald-600">Daftar Nasabah</a>
            @endauth
        </div>
    </header>

    <!-- Flash Alert Messages -->
    @if(session('success') || session('error') || $errors->any())
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3 shadow-sm">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
                    <p class="text-xs sm:text-sm font-semibold">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center gap-3 shadow-sm mt-2">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 text-lg"></i>
                    <p class="text-xs sm:text-sm font-semibold">{{ session('error') }}</p>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 shadow-sm mt-2">
                    <ul class="list-disc list-inside text-xs space-y-1 font-medium">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endif

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Clean Footer -->
    <footer class="bg-slate-900 text-slate-400 text-xs border-t border-slate-800 mt-16 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-recycle text-emerald-400 text-base"></i>
                <span class="font-bold text-white">Sistem Pengelolaan Bank Sampah</span>
                <span class="text-slate-500">&bull;</span>
                <span class="text-slate-400">Laravel + PostgreSQL + Tailwind CSS</span>
            </div>
            <div class="text-slate-500 text-center sm:text-right">
                Aktor: <strong>Nasabah</strong> & <strong>Pengurus/Admin</strong>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
