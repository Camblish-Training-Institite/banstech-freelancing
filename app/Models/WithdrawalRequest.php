<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WithdrawalRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'method',
        'status',
        'destination_details',
        'paypal_recipient_email',
        'paypal_batch_id',
        'paypal_payout_item_id',
        'gateway_response',
        'confirmed_at',
        'requested_at',
        'processed_at',
        'reviewed_by_admin_id',
        'failure_reason',
    ];

    protected $casts = [
        'destination_details' => 'array',
        'gateway_response' => 'array',
        'confirmed_at' => 'datetime',
        'requested_at' => 'datetime',
        'processed_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewedByAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by_admin_id');
    }
}
