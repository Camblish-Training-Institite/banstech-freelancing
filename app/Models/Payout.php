<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    /** @use HasFactory<\Database\Factories\PayoutFactory> */
    use HasFactory;

    protected $fillable = [
        'freelancer_id',
        'contract_id',
        'milestone_id',
        'contest_id',
        'amount',
        'source_currency',
        'paypal_currency',
        'paypal_amount',
        'exchange_rate',
        'method',
        'status',
        'requested_at',
        'processed_at',
        'paypal_recipient_email',
        'paypal_batch_id',
        'paypal_payout_item_id',
        'gateway_response',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'processed_at' => 'datetime',
        'paypal_amount' => 'decimal:2',
        'exchange_rate' => 'decimal:8',
        'gateway_response' => 'array',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    public function milestone()
    {
        return $this->belongsTo(Milestone::class, 'milestone_id');
    }

    public function contest()
    {
        return $this->belongsTo(Contest::class, 'contest_id');
    }
}
