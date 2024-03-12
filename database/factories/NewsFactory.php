<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
class NewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userIds = User::pluck('id')->toArray();
        $userId = $this->faker->randomElement($userIds);
            return [
                'title' => $this->faker->sentence,
                'content' => $this->faker->paragraphs(6, true),
                'author_id' =>$userId, // Assuming you have a User factory
                'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            ];
    }
}
