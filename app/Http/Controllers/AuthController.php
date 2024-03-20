<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index()
    {

        return view('auth.login', [
            'title' => 'Login',
        ]);
    }

    public function authenticate(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required',
        'password' => 'required'
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        // Ambil pengguna yang telah diautentikasi
        $user = Auth::user();

        // Periksa role pengguna
        if ($user->role === 'admin') {
            // Jika pengguna adalah admin, arahkan ke dashboard admin
            Alert::success('Success', 'Login success as admin!');
            return redirect()->intended('/dashboard');
        } else {
            // Jika pengguna adalah user biasa, arahkan ke halaman home
            Alert::success('Success', 'Login success as user!');
            return redirect()->intended('/home');
        }
    } else {
        // Jika kredensial tidak valid, kembali ke halaman login dengan pesan kesalahan
        Alert::error('Error', 'Login failed!');
        return redirect('/login');
    }
}


    public function register()
    {
        return view('auth.register', [
            'title' => 'Register',
        ]);
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'role' => 'required',
            'password' => 'required',
            'passwordConfirm' => 'required|same:password'
        ]);

        $validated['password'] = Hash::make($request['password']);

        $user = User::create($validated);

        Alert::success('Success', 'Register user has been successfully !');
        return redirect('/login');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();
        Alert::success('Success', 'Log out success !');
        return redirect('/login');
    }
}
