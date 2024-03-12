<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userIds = User::pluck('id')->toArray();
        $productIds = Product::pluck('id')->toArray();

        // Select a random user, product, and store
        $userId = $this->faker->randomElement($userIds);
        $productId = $this->faker->randomElement($productIds);
        $storeId = Product::find($productId)->store_id; // Get the store ID associated with the product

        return [
            'user_id' => $userId,
            'product_id' => $productId,
            'store_id' => $storeId,
            'communication_rating' => $this->faker->numberBetween(1, 5),
            'product_rating' => $this->faker->numberBetween(1, 5),
            'shipping_speed_rating' => $this->faker->numberBetween(1, 5),
            'price_rating' => $this->faker->numberBetween(1, 5),
            'feedback' => $this->faker->randomElement(['positive', 'neutral', 'negative']),
            'comment' => $this->faker->paragraph,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
