<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
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
        Order::factory(5000)->create([
            'total' => 0,
        ])
            ->each(function ($order) use ($faker) {
                $orderTotal = 0;

                foreach (range(1, rand(2, 10)) as $item) {
                    $quantity = $faker->numberBetween(1, 10);
                    $productId = $faker->randomElement(Product::pluck('id'));
                    $product = Product::find($productId);
                    $total = $quantity * $product->price;

                    $order->products()->attach(
                        $productId,
                        [
                            'quantity' => $quantity,
                            'price' => $product->price,
                            'total' => $total
                        ]
                    );
                    $orderTotal += $total;
                };
                $randomTimeStamp = Carbon::today()->subDays(rand(0, 365));

                $order = Order::find($order->id);
                $order->total = $orderTotal;
                $order->updated_at = $randomTimeStamp;
                $order->created_at = $randomTimeStamp;
                $order->save();
            });
    }
}
