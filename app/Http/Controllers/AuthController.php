<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\VercelApi;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session('token')) return redirect()->route('dashboard');
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate(['phone' => 'required', 'password' => 'required']);

        $data = VercelApi::login($request->phone, $request->password);

        if (isset($data['error']) || !isset($data['token'])) {
            $msg = $data['error'] ?? $data['message'] ?? 'Invalid phone number or password.';
            return back()->withErrors(['phone' => $msg])->withInput();
        }

        session(['token' => $data['token'], 'user' => $data['user']]);
        return redirect()->route('dashboard');
    }

    public function showRegister()
    {
        if (session('token')) return redirect()->route('dashboard');
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'phone'    => 'required|string|max:20',
            'password' => 'required|min:6|confirmed',
            'dob'      => 'required|date',
        ]);

        $data = VercelApi::register($request->name, $request->phone, $request->password, $request->dob);

        if (isset($data['error']) || !isset($data['token'])) {
            $msg = $data['error'] ?? $data['message'] ?? 'Registration failed.';
            return back()->withErrors(['phone' => $msg])->withInput();
        }

        session(['token' => $data['token'], 'user' => $data['user']]);
        return redirect()->route('dashboard');
    }

    public function showForgot()
    {
        return view('auth.forgot');
    }

    public function forgot(Request $request)
    {
        $request->validate([
            'phone'    => 'required',
            'dob'      => 'required|date',
            'password' => 'required|min:6|confirmed',
        ]);

        $data = VercelApi::forgotPassword($request->phone, $request->dob, $request->password);

        if (isset($data['error'])) {
            return back()->withErrors(['phone' => $data['error']])->withInput();
        }

        return redirect()->route('login')->with('success', 'Password reset successfully. Please login.');
    }

    public function showProfile()
    {
        $user = session('user');
        return view('auth.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100']);
        $user = session('user');
        $user['name'] = $request->name;
        session(['user' => $user]);
        return back()->with('success', 'Profile updated.');
    }

    public function logout()
    {
        session()->forget(['token', 'user']);
        return redirect()->route('login');
    }
}
