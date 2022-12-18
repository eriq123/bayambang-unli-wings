<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRoleId = Role::where('name', 'Admin')->first()->id;
        $shops = Shop::all();
        $arrAdmins = [
            [
                'name' => 'Pasadena Admin',
                'email' => 'pasadena@bayambangunliwings.com',
                'password' => bcrypt('pasadenaadmin'),
                'role_id' => $adminRoleId,
                'shop_id' => $shops->where('name', 'Pasadena')->first()->id,
            ],
            [
                'name' => 'Marsvin Admin',
                'email' => 'marsvin@bayambangunliwings.com',
                'password' => bcrypt('marsvinadmin'),
                'role_id' => $adminRoleId,
                'shop_id' => $shops->where('name', 'Marsvin')->first()->id,
            ],
            [
                'name' => 'WingScape Admin',
                'email' => 'wingscape@bayambangunliwings.com',
                'password' => bcrypt('wingscapeadmin'),
                'role_id' => $adminRoleId,
                'shop_id' => $shops->where('name', 'WingScape')->first()->id,
            ],
            [
                'name' => 'Winging It Admin',
                'email' => 'wingingit@bayambangunliwings.com',
                'password' => bcrypt('wingingitadmin'),
                'role_id' => $adminRoleId,
                'shop_id' => $shops->where('name', 'Winging It')->first()->id,
            ],
        ];
        User::create([
            'name' => 'Super Admin',
            'email' => 'super@admin.com',
            'password' => bcrypt('admin'),
            'role_id' => Role::where('name', 'Super Admin')->first()->id,
        ]);
        User::insert($arrAdmins);
        User::factory(10)->create();
    }
}
