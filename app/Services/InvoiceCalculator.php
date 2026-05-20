<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\DiscountCode;

class InvoiceCalculator
{
    /**
     * Calculate total invoice amounts based on items and a coupon code.
     *
     * @param array $items Invoice lines/items received from the request.
     * @param DiscountCode|null $discountCode The applied coupon/discount code if available.
     * @return array Calculated totals and mapped items ready for database insertion.
     */
    public function calculate(array $items, ?DiscountCode $discountCode = null): array
    {
        $subTotal = 0.0000;
        $taxTotal = 0.0000;
        $calculatedItems = [];

        // 1. Calculate each item individually (Item-Level Tax compliance)
        foreach ($items as $item) {
            $qty = (float) ($item['quantity'] ?? 1);
            $unitPrice = (float) ($item['unit_price'] ?? 0);
            $taxRate = (float) ($item['tax_rate'] ?? 0); // e.g., 15.00 for 15%
            $itemDiscount = (float) ($item['discount_total'] ?? 0); // Line-level discount if any

            // Line Subtotal = (Quantity * Unit Price) - Line Discount
            $itemSubTotal = ($qty * $unitPrice) - $itemDiscount;

            // Calculate specific tax amount for this individual line item
            $itemTaxTotal = $itemSubTotal * ($taxRate / 100);

            $subTotal += $itemSubTotal;
            $taxTotal += $itemTaxTotal;

            // Prepare the structured array for saving into 'invoice_items' table later
            $calculatedItems[] = [
                'item_id' => $item['item_id'] ?? null,
                'name' => $item['name'],
                'quantity' => $qty,
                'unit_price' => $unitPrice,
                'tax_rate' => $taxRate,
                'tax_total' => $itemTaxTotal,
                'discount_total' => $itemDiscount,
                'sub_total' => $itemSubTotal + $itemTaxTotal, // Grand total for this line including its tax
            ];
        }

        // 2. Calculate the global invoice discount based on the applied coupon
        $discountTotal = 0.0000;
        if ($discountCode && $this->isValidCoupon($discountCode, $subTotal)) {
            if ($discountCode->type === 'percentage') {
                $discountTotal = $subTotal * ($discountCode->value / 100);
            } elseif ($discountCode->type === 'fixed') {
                $discountTotal = (float) $discountCode->value;
            }

            // Safety guard: Global discount cannot exceed the invoice subtotal
            if ($discountTotal > $subTotal) {
                $discountTotal = $subTotal;
            }
        }

        // 3. Final calculations (Grand Total)
        // Grand Total = Subtotal + Total Taxes - Total Discount
        $grandTotal = ($subTotal + $taxTotal) - $discountTotal;

        return [
            'totals' => [
                'sub_total' => $subTotal,
                'tax_total' => $taxTotal,
                'discount_total' => $discountTotal,
                'grand_total' => max(0.0000, $grandTotal), // Ensures Grand Total never goes negative
                'paid_total' => 0.0000, // A brand new invoice starts with 0.0000 paid amount
            ],
            'items' => $calculatedItems
        ];
    }

    /**
     * Validate the terms and conditions of the coupon code.
     *
     * @param DiscountCode $coupon The coupon code being validated.
     * @param float $subTotal The current subtotal of the invoice.
     * @return bool True if valid, false otherwise.
     */
    private function isValidCoupon(DiscountCode $coupon, float $subTotal): bool
    {
        if (!$coupon->is_active) return false;
        if ($subTotal < $coupon->min_invoice_amount) return false;
        if ($coupon->max_uses && $coupon->uses_count >= $coupon->max_uses) return false;

        $now = now();
        if ($coupon->starts_at && $now->lt($coupon->starts_at)) return false;
        if ($coupon->expires_at && $now->gt($coupon->expires_at)) return false;

        return true;
    }
}
