<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class InvoiceNumberSequence extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceNumberSequenceFactory> */
    use HasFactory;

    protected $fillable = [
        'team_id',
        'prefix',
        'next_number',
        'digits_length',
        'reset_strategy',
        'last_reset_date',
    ];

    protected $casts = [
        'next_number' => 'integer',
        'digits_length' => 'integer',
        'last_reset_date' => 'date',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function generateNextNumber(int $teamId): string
    {
        return DB::transaction(function () use ($teamId) {
            $sequence = self::where('team_id', $teamId)
                ->lockForUpdate()
                ->firstOrCreate(
                    ['team_id' => $teamId],
                    [
                        'prefix' => 'INV-' . date('Y') . '-',
                        'next_number' => 1,
                        'digits_length' => 5
                    ]
                );
            $formattedNumber = str_pad(
                (string) $sequence->next_number,
                $sequence->digits_length,
                0,
                STR_PAD_LEFT
            );

            $invoiceNumber = $sequence->prefix . $formattedNumber;

            $sequence->increment('next_number');
            return $invoiceNumber;
        });
    }
}
