<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Item;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Item>
 */
class ItemFactory extends Factory
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
            'branch_id' => Branch::factory(),
            'name' => fake()->words(2, true),
            'description' => fake()->sentence(),
            'sku' => 'PROD-' . fake()->unique()->numberBetween(1000, 9999),
            'price' => fake()->randomFloat(4, 10, 2000),
            'quantity' => fake()->randomNumber(5, 150),
            'is_taxable' => fake()->boolean(90),
        ];
    }
}
