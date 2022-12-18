<?php

namespace Database\Seeders;

use App\Models\Shop;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Shop::insert([
            [
                'name' => 'WingScape',
                'slug' => Str::slug('WingScape', '-'),
            ],
            [
                'name' => 'Pasadena',
                'slug' => Str::slug('Pasadena', '-'),
            ],
            [
                'name' => 'Marsvin',
                'slug' => Str::slug('Marsvin', '-'),
            ],
            [
                'name' => 'Winging It',
                'slug' => Str::slug('Winging It', '-'),
            ],
        ]);
    }
}
