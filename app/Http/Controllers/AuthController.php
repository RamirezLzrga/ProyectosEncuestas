<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\ActivityLog;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Log Activity
            ActivityLog::create([
                'user_id' => Auth::id(),
                'user_email' => Auth::user()->email,
                'action' => 'login',
                'description' => 'Inicio de sesión exitoso',
                'type' => 'auth',
                'ip_address' => $request->ip()
            ]);

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        // Log Activity
        ActivityLog::create([
            'user_id' => $user->id,
            'user_email' => $user->email,
            'action' => 'register',
            'description' => 'Nuevo usuario registrado',
            'type' => 'user',
            'ip_address' => $request->ip()
        ]);

        return redirect('/dashboard');
    }

    public function logout(Request $request)
    {
        // Log Activity before logout
        if (Auth::check()) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'user_email' => Auth::user()->email,
                'action' => 'logout',
                'description' => 'Cierre de sesión',
                'type' => 'auth',
                'ip_address' => $request->ip()
            ]);
        }

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
