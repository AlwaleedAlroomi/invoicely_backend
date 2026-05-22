<?php

namespace App\Models;

use App\Traits\ScopedResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentFactory> */
    use HasFactory, SoftDeletes, ScopedResource;

    protected $fillable = [
        'team_id',
        'invoice_id',
        'remote_id',
        'payment_date',
        'amount',
        'payment_method',
        'reference_number',
        'notes',
    ];

    protected $casts = [
        'amount' => 'float',
        'payment_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];


    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }


    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
