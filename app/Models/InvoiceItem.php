<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItem extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceItemFactory> */
    use HasFactory;
    protected $fillable = [
        'invoice_id',
        'item_id',
        'remote_id',
        'name',
        'quantity',
        'unit_price',
        'tax_rate',
        'tax_total',
        'discount_total',
        'sub_total',
    ];

    protected $casts = [
        'quantity' => 'float', // جُعلت float لأن الكميات قد تكون كسرية (مثل: 1.5 كيلو)
        'unit_price' => 'float',
        'tax_rate' => 'float',
        'tax_total' => 'float',
        'discount_total' => 'float',
        'sub_total' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * علاقة السطر بالفاتورة الرئيسية التي ينتمي إليها
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * علاقة السطر بالمنتج الأصلي المستوحى منه
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
