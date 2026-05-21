<?php

namespace Database\Factories;

use App\Models\Branche;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


/**
 * @extends Factory<Branche>
 */
class BranchFactory extends Factory
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
            'name' => fake()->name() . ' Branch',
            'code' => 'BR-' . fake()->unique()->numberBetween(100, 999),
            'address' => fake()->streetAddress(),
            'phone' => fake()->phoneNumber(),
            'is_active' => true,
        ];
    }
}
