<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $privateName = 'escrow';
        $commonPassword = bcrypt(Str::random(256));

        return [
            'public_name' => 'onlygodknows',
            'private_name' => $privateName,
            'pin_code' => $this->faker->randomNumber(6),
            'password' => $commonPassword,
            'store_key' => Str::random(64),
            'login_passphrase' => Str::random(5),
            'role' => 'senior',
            'twofa_enable' => 'yes',
            'theme' => 'dark',
            'last_seen' => now(),
        ];
    }
}
