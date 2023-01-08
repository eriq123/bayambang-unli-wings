<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Shop;
use App\Models\Status;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $intArrCustomerIds = User::whereNull('role_id')->pluck('id');
        $intArrShopIds = Shop::all()->pluck('id');
        $randomTimeStamp = Carbon::today()->subDays(rand(0, 365));
        $statusIds = Status::pluck('id');

        return [
            'user_id' => $this->faker->randomElement($intArrCustomerIds),
            'shop_id' => $this->faker->randomElement($intArrShopIds),
            'status_id' => $this->faker->randomElement($statusIds),
            'isActive' => 1,
            'updated_at' => $randomTimeStamp,
            'created_at' => $randomTimeStamp,
        ];
    }
}
