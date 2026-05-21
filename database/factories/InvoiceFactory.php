<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Client;
use App\Models\DiscountCode;
use App\Models\Invoice;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Invoice>
 */
class InvoiceFactory extends Factory
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
            'branch_id' => Branch::factory(),
            'client_id' => Client::factory(),
            'discount_code_id' => null,
            'remote_id' => Str::uuid(),
            'invoice_number' => 'INV-2026-' . fake()->unique()->numberBetween(10000, 99999),
            'invoice_date' => now(),
            'due_date' => now()->addDays(14),
            'sub_total' => 0.0000,
            'tax_total' => 0.0000,
            'discount_total' => 0.0000,
            'grand_total' => 0.0000,
            'paid_total' => 0.0000,
            'status' => fake()->randomElement(['draft', 'sent', 'paid', 'partially_paid', 'overdue']),
            'currency' => 'USD',
            'notes' => fake()->optional()->paragraph(),
        ];
    }
}
