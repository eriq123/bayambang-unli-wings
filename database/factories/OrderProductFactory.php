<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderProduct>
 */
class OrderProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'product_id' => $this->faker->randomElement(Product::pluck('id')),
            'quantity' => $this->faker->numberBetween(1, 20),
        ];
    }

    public function withoutOrder()
    {
        return $this->state(fn (array $attributes) => [
            'order_id' => null,
        ]);
    }
}
