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

        $admins = User::where('role_id', $role->id)->with('shop')->get();
        return view('index', compact('admins'));
    }
}
