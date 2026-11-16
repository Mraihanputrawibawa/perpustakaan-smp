<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate(); // cegah session fixation
        ActivityLog::record('login', 'User login: '.$request->user()->email);

        return redirect()->intended(route('dashboard'));
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'kelas' => ['nullable', 'string', 'max:10'],
            // Password lemah ditolak: minimal 8 karakter, kombinasi huruf & angka
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        // Peran tidak pernah diambil dari input publik: pendaftar selalu 'siswa'
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'kelas' => $data['kelas'] ?? null,
            'password' => $data['password'], // ter-hash otomatis (cast 'hashed')
            'role' => 'siswa',
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        ActivityLog::record('register', 'User baru: '.$user->email);

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        ActivityLog::record('logout', 'User logout: '.$request->user()?->email);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
