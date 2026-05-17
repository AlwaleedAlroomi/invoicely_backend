<?php

namespace Database\Factories;

use App\Models\InvoiceNumberSequence;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InvoiceNumberSequence>
 */
class InvoiceNumberSequenceFactory extends Factory
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
            'prefix' => 'INV-' . date('Y') . '-',
            'next_number' => 1,
            'digits_length' => 5,
            'reset_strategy' => 'never',
            'last_reset_date' => null,
        ];
    }
}
