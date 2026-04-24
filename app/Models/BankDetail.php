<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'account_holder_name',
        'bank_name',
        'account_number',
        'account_type',
        'branch_code',
        'swift_code',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getMaskedAccountNumberAttribute(): ?string
    {
        if (! $this->account_number) {
            return null;
        }

        $visibleDigits = substr($this->account_number, -4);

        return '...' . $visibleDigits;
    }
}
