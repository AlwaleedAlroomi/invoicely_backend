<?php

namespace App\Models;

use App\Traits\BelongsToTeam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceFactory> */
    use HasFactory, BelongsToTeam, SoftDeletes;

    protected $fillable = [
        'team_id',
        'branch_id',
        'client_id',
        'discount_code_id',
        'remote_id',
        'invoice_number',
        'invoice_date',
        'due_date',
        'sub_total',
        'tax_total',
        'discount_total',
        'grand_total',
        'paid_total',
        'status',
        'currency',
        'notes',
    ];

    protected $casts = [
        'sub_total' => 'float',
        'tax_total' => 'float',
        'discount_total' => 'float',
        'grand_total' => 'float',
        'paid_total' => 'float',
        'invoice_date' => 'datetime',
        'due_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function discountCode(): BelongsTo
    {
        return $this->belongsTo(DiscountCode::class, 'discount_code_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'invoice_id');
    }
}
