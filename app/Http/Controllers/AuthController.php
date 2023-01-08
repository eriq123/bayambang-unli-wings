<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Shop;
use App\Models\User;
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

    public function add()
    {
        $roles = Role::all();
        $shops = Shop::all();
        return view('super-admin.admin-edit', compact('roles', 'shops'));
    }

    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::all();
        $shops = Shop::all();
        return view('super-admin.admin-edit', compact('user', 'roles', 'shops'));
    }

    public function update(Request $request)
    {
        $input = [
            'name' => 'required',
            'email' => 'required|email',
            'shop_id' => 'required',
        ];

        if (!$request->id) {
            $input['password'] = 'required|confirmed';
        }
        $request->validate($input);

        $role = Role::where('name', 'Admin')->first();

        if ($request->id) {
            $user = User::find($request->id);
        } else {
            $user = new User();
        }

        $user->name = $request->name;
        $user->email = $request->email;
        if (!$user->role_id) {
            $user->role_id = $role->id;
        }
        $user->shop_id = $request->shop_id;

        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('user.edit', ['id' => $user->id])->withSuccess('User updated successfully.');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        $user = User::findOrFail($request->id);
        $user->delete();
        return redirect('/')->withSuccess('User deleted.');
    }
}
