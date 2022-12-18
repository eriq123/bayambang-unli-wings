<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function postLogin(Request $request)
    {
        $credentials = $request->validate(
            [
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]
        );

        $superAdminId = Role::where('name', 'Super Admin')->first()->id;

        if (Auth::attempt($credentials)) {
            if ($request->isSuperAdmin === 'true' && Auth::user()->role_id !== $superAdminId) {
                $this->logoutAndFlushSession();
                return $this->redirectToLoginWithErrors();
            }

            if (!isset($request->isSuperAdmin) && Auth::user()->role_id === $superAdminId) {
                $this->logoutAndFlushSession();
                return $this->redirectToLoginWithErrors();
            }

            $request->session()->regenerate();
            return redirect()->route('index');
        }

        return $this->redirectToLoginWithErrors();
    }

    private function redirectToLoginWithErrors()
    {
        return redirect()->route('login')->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    private function logoutAndFlushSession()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
    }

    public function logout()
    {
        $this->logoutAndFlushSession();
        return redirect('/');
    }
}
