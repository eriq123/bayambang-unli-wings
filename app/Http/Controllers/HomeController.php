<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Shop;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userRole = Role::find($user->role_id);
        $status = Status::find(1);
        $shop = Shop::find($user->shop_id);

        if ($userRole->name == 'Admin') {
            return redirect(
                route('order.byStatus', [
                    'shop' => $shop->slug,
                    'slug' => $status->slug
                ])
            );
        }

        return view('index');
    }
}
