<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\NasabahProfile;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if (in_array($user->role, ['admin', 'petugas'])) {
                return redirect()->intended(route('admin.dashboard'))->with('success', 'Selamat datang kembali, ' . $user->name);
            }

            return redirect()->intended(route('nasabah.dashboard'))->with('success', 'Selamat datang di EcoBank, ' . $user->name);
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi tidak cocok dengan catatan kami.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => 'nasabah',
            'password' => Hash::make($validated['password']),
        ]);

        $year = date('Y');
        $randomNum = str_pad(User::count(), 4, '0', STR_PAD_LEFT);
        $noRekening = "BS-{$year}-{$randomNum}";

        NasabahProfile::create([
            'user_id' => $user->id,
            'no_rekening' => $noRekening,
            'balance' => 0,
            'points' => 100, // Bonus pendaftaran 100 poin!
            'co2_saved_kg' => 0,
            'address' => $validated['address'],
            'qr_code_token' => 'QR-' . strtoupper(Str::slug($user->name)) . '-' . $randomNum,
            'status' => 'active',
        ]);

        Auth::login($user);

        return redirect()->route('nasabah.dashboard')->with('success', 'Akun berhasil dibuat! Anda mendapatkan bonus 100 Eco-Points.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah keluar dari sistem.');
    }

    /**
     * Quick switch simulation for demo testing
     */
    public function quickLogin($role)
    {
        $user = null;
        if ($role === 'admin') {
            $user = User::where('role', 'admin')->first();
        } elseif ($role === 'petugas') {
            $user = User::where('role', 'petugas')->first();
        } elseif ($role === 'nasabah') {
            $user = User::where('role', 'nasabah')->first();
        }

        if ($user) {
            Auth::login($user);
            if (in_array($user->role, ['admin', 'petugas'])) {
                return redirect()->route('admin.dashboard')->with('success', 'Beralih ke mode ' . ucfirst($role) . ': ' . $user->name);
            }
            return redirect()->route('nasabah.dashboard')->with('success', 'Beralih ke akun Nasabah: ' . $user->name);
        }

        return redirect('/')->with('error', 'Pengguna demo tidak ditemukan.');
    }
}
