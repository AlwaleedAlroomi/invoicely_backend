<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'remote_id' => Str::uuid(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'website' => fake()->url(),
            'address_line_1' => fake()->streetAddress(),
            'city' => fake()->city(),
            'country' => fake()->country(),
            'currency' => 'USD',
            'is_active' => true,
            'notes' => fake()->sentence(),
        ];
    }
}
