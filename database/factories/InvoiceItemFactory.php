<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<InvoiceItem>
 */
class InvoiceItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 5);
        $unitPrice = fake()->randomFloat(4, 20, 500);
        $subTotal = $quantity * $unitPrice;
        $taxRate = fake()->randomElement([0.00, 5.00, 15.00]);
        $taxTotal = $subTotal * ($taxRate / 100);

        return [
            'invoice_id' => Invoice::factory(),
            'item_id' => Item::factory(),
            'remote_id' => Str::uuid(),
            'name' => fake()->words(2, true),
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'tax_rate' => $taxRate,
            'tax_total' => $taxTotal,
            'discount_total' => 0.0000,
            'sub_total' => $subTotal,
        ];
    }
}
