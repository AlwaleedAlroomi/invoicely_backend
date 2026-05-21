<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
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
            'invoice_id' => Invoice::factory(),
            'remote_id' => Str::uuid(),
            'payment_date' => now(),
            'amount' => fake()->randomFloat(4, 10, 1000),
            'payment_method' => fake()->randomElement(['cash', 'bank_transfer', 'stripe', 'mada']),
            'reference_number' => 'TXN-' . fake()->bothify('#####????'),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
