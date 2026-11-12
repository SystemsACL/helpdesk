<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return Auth::check() ? redirect()->route('panel') : view('auth.login');
    }

    public function login(Request $request)
    {
        $cred = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($cred + ['is_active' => true], $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('panel'));
        }

        return back()->withErrors(['email' => 'Correo o contraseña incorrectos.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function account()
    {
        return view('auth.account');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(10)],
        ], [
            'current_password.current_password' => 'La contraseña actual no es correcta.',
            'password.confirmed' => 'Las contraseñas nuevas no coinciden.',
            'password.min' => 'Mínimo 10 caracteres.',
        ]);

        $request->user()->update([
            'password' => $request->password,
            'must_change_password' => false,
        ]);

        return redirect()->route('panel')->with('ok', 'Contraseña actualizada.');
    }
}
