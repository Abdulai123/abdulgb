<?php

namespace Database\Factories;

use App\Models\Store;
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
    public function definition(): array
    {
        $storIds = Store::pluck('id')->toArray();
        // Shuffle the user IDs to avoid repetition
        shuffle($storIds);

        $storeId = array_pop($storIds);
        $isDigital = $this->faker->boolean; // Randomly decide if it's a digital product

        return [
            'store_id' => $storeId,
            'quantity' => $this->faker->numberBetween(1, 100),
            'sold'    => $this->faker->numberBetween(1, 100),
            'product_name' => $this->faker->sentence($this->faker->numberBetween(1, 8)),
            'product_description' => $this->faker->paragraph,
            'price' => $this->faker->randomFloat(2, 5, 500),
            'product_type' => $isDigital ? 'digital' : $this->faker->randomElement(['digital', 'physical']),
            'ship_from' => $this->faker->country,
            'payment_type' => $this->faker->randomElement(['Escrow', 'FE']),
            'ship_to' => $this->faker->randomElement(['World Wide', 'North America', 'Europe', 'Asia']),
            'parent_category_id' => $this->faker->numberBetween(1, 8),
            'sub_category_id' => $this->faker->numberBetween(9, 27),
            'return_policy' => $this->faker->paragraph,
            'auto_delivery_content' => $isDigital ? $this->faker->paragraph : null,
            'disputes_lost' => $this->faker->numberBetween(0, 10),
            'disputes_won' => $this->faker->numberBetween(0, 10),
            'status' => $this->faker->randomElement(['Active']),
        ];
    }
}
