<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $initialStatusId = Status::where('name', (new StatusSeeder)->getStatus()[0])->first()->id;

        Order::factory(100)->create([
            'status_id' => $initialStatusId,
        ])
            ->each(function ($order) use ($faker) {
                foreach (range(1, rand(2, 10)) as $item) {
                    $order->products()->attach(
                        $faker->randomElement(Product::pluck('id')),
                        [
                            'quantity' => $faker->numberBetween(1, 20)
                        ]
                    );
                };
            });
    }
}
