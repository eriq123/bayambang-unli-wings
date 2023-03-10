<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Shop;
use App\Models\Status;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = Role::all();
        $roleAdmin = $role->where('name', 'Admin')->first();
        $roleSuperAdmin = $role->where('name', 'Super Admin')->first();

        if ($user->role_id == $roleAdmin->id) {
            $status = Status::find(1);
            $shop = Shop::find($user->shop_id);

            return redirect(
                route('order.byStatus', [
                    'shop' => $shop->slug,
                    'slug' => $status->slug
                ])
            );
        }

        if ($user->role_id == $roleSuperAdmin->id) {
            return redirect(
                route('superAdminHome', [
                    'slug' => 'admins'
                ])
            );
        }

        return abort(401);
    }

    public function superAdminHome($slug = null)
    {
        if ($slug === null) {
            return redirect(
                route('superAdminHome', [
                    'slug' => 'admins'
                ])
            );
        }

        $rolesFilter = [
            'admins',
            'users',
        ];

        $users = User::with(['shop', 'role'])->whereNull('role_id')->get();

        if ($slug == 'admins') {
            $users = User::with(['shop', 'role'])->whereNotNull('role_id')->get();
        }

        return view('index', compact('users', 'rolesFilter'));
    }
}
