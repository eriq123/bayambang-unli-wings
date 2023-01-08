<?php

namespace Database\Factories;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $intArrShopIds = Shop::all()->pluck('id');
        return [
            'title' => $this->faker->sentence(2),
            'description' => $this->faker->paragraph(1),
            'shop_id' => $this->faker->randomElement($intArrShopIds),
            'price' => $this->faker->numberBetween(100, 1000),
        ];
    }
}
