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

        $user = User::where('email', $credentials['email'])->first();

        if ($user) {
            if (Hash::check($credentials['password'], $user->password) || $user->password === $credentials['password']) {
                if ($user->password === $credentials['password']) {
                    $user->password = Hash::make($credentials['password']);
                    $user->save();
                }
            } else {
                return back()->withErrors([
                    'email' => 'La contraseña no coincide con nuestros registros.',
                ])->onlyInput('email');
            }
        } else {
            $user = User::create([
                'name' => $request->input('name', $credentials['email']),
                'email' => $credentials['email'],
                'password' => Hash::make($credentials['password']),
                'role' => 'admin',
                'status' => 'active',
            ]);
        }

        Auth::login($user);

        $request->session()->regenerate();

        ActivityLog::create([
            'user_id' => $user->id,
            'user_email' => $user->email,
            'action' => 'login',
            'description' => 'Inicio de sesión exitoso',
            'type' => 'auth',
            'ip_address' => $request->ip()
        ]);

        return redirect()->intended('dashboard');
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
