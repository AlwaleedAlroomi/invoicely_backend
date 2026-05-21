<?php

namespace Database\Factories;

use App\Models\DiscountCode;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


/**
 * @extends Factory<DiscountCode>
 */
class DiscountCodeFactory extends Factory
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
            'code' => strtoupper(fake()->bothify('????##')),
            'type' => fake()->randomElement(['percentage', 'fixed']),
            'value' => fake()->randomFloat(4, 5, 100),
            'min_invoice_amount' => fake()->randomFloat(4, 0, 500),
            'max_uses' => fake()->numberBetween(50, 500),
            'uses_count' => 0,
            'starts_at' => now(),
            'expires_at' => fake()->dateTimeBetween('+1 day', '+1 month'),
            'is_active' => true,
        ];
    }
}
