<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userIds = User::pluck('id')->toArray();
        // Ensure that each user is associated with only one wallet
        // Shuffle the user IDs to avoid repetition
        shuffle($userIds);

        $userId = array_pop($userIds);

        return [
            'user_id' => $userId,
            'store_name' => $this->faker->company,
            'store_description' => $this->faker->paragraph,
            'store_pgp' => Str::random(200),
            'store_key' => Str::random(64),
            'status' => $this->faker->randomElement(['active']),
            'selling' => $this->faker->randomElement(['weeds, seeds, apple, juice', 'cocacola, pips, sesur, wui', 'mango, orange, fruts, neom']),
            'ship_from' => $this->faker->country,
            'ship_to' => $this->faker->country,
            'last_updated' => now(),
        ];
    }
}
