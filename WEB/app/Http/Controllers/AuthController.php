<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        // If already authenticated, redirect to dashboard
        if (session('authenticated') === true) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle the login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $expectedUsername = env('AUTH_USERNAME', 'admin');
        $expectedPassword = env('AUTH_PASSWORD', 'rotikita');

        if ($request->username === $expectedUsername && $request->password === $expectedPassword) {
            session(['authenticated' => true]);
            return redirect()->route('dashboard')->with('success', 'Selamat datang kembali, Admin RotiKita!');
        }

        return back()
            ->withInput($request->only('username'))
            ->withErrors(['login_error' => 'Username atau Password salah!']);
    }

    /**
     * Handle logout.
     */
    public function logout()
    {
        session()->forget('authenticated');
        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar sistem.');
    }
}
