<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Shop;
use App\Models\Status;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index($slug = null)
    {
        $user = Auth::user();
        $role = Role::where('name', 'Admin')->first();

        if ($user->role_id == $role->id) {
            $status = Status::find(1);
            $shop = Shop::find($user->shop_id);

            return redirect(
                route('order.byStatus', [
                    'shop' => $shop->slug,
                    'slug' => $status->slug
                ])
            );
        }

        $rolesFilter = [
            'admins',
            'users',
        ];
        if ($slug === null) return redirect('/admins');
        $users = $this->getUserByType($slug);

        return view('index', compact('users', 'rolesFilter'));
    }

    private function getUserByType($type)
    {
        $user = User::with(['shop', 'role'])->whereNull('role_id')->get();
        if ($type == 'admins') {
            $user = User::with(['shop', 'role'])->whereNotNull('role_id')->get();
        }
        return $user;
    }
}
